<?php

namespace app\controllers;
use Yii;
use app\controllers\CommonController;
use app\models\ShopOrder;
use app\models\ShopOrderDetail;
use app\models\ShopCart;
use app\models\ShopProduct;
use app\models\ShopAddress;
use yii\db\Exception;
use app\models\User;
use app\models\Pay;
use dzer\express\Express;
use yii\filters\AccessControl;
class OrderController extends CommonController
{   
    
    

    protected $mustlogin = ['index', 'check', 'add', 'confirm', 'pay', 'getexpress', 'received'];
    protected $verbs = [
        'confirm' => ['post']
    ];
    
   
    public $layout=false;
    public function actionIndex()
    {
    	$this->layout = "loser2";
        // Yii::$app->session->removeAll();
        // if (Yii::$app->session['isLogin'] != 1) {
        //     return $this->redirect(['member/auth']);
        // }
        // $loginname = Yii::$app->user->userid;
        // $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $userid =Yii::$app->user->id;
        $orders = ShopOrder::getProducts($userid);
        return $this->render("index", ['orders' => $orders]);
    }
    public function actionCheck()
    {
    	$this->layout="loser1";
        // if (Yii::$app->session['isLogin'] != 1) {
        //     return $this->redirect(['member/auth']);
        // }
        $orderid = Yii::$app->request->get('orderid');
        $status = ShopOrder::find()->where('orderid = :oid', [':oid' => $orderid])->one()->status;
        if ($status != ShopOrder::CREATEORDER && $status != ShopOrder::CHECKORDER) {
            return $this->redirect(['order/index']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $addresses = ShopAddress::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();

        $details = ShopOrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->asArray()->all();
        $data = [];
        foreach($details as $detail) {
            $model = ShopProduct::find()->where('productid = :pid' , [':pid' => $detail['productid']])->one();
            $detail['title'] = $model->title;
            $detail['cover'] = $model->cover;
            $data[] = $detail;
        }
        $express = Yii::$app->params['express'];
        $expressPrice = Yii::$app->params['expressPrice'];
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
    }
     public function actionAdd()
    {
        // if (Yii::$app->session['isLogin'] != 1) {
        //     return $this->redirect(['member/auth']);
        // }
        $transaction = Yii::$app->db->beginTransaction();
        try {

            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $ordermodel = new ShopOrder;
                $ordermodel->scenario = 'add';
                $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => Yii::$app->session['loginname'], ':email' => Yii::$app->session['loginname']])->one();
                if (!$usermodel) {
                    throw new \Exception();
                }
                $userid = $usermodel->userid;
                $ordermodel->userid = $userid;
                $ordermodel->status = ShopOrder::CREATEORDER;
                $ordermodel->createtime = time();
                if (!$ordermodel->save()) {
                    throw new \Exception();
                }
                //获取id
                $orderid = $ordermodel->getPrimaryKey();
                foreach ($post['OrderDetail'] as $product) {
                    $model = new ShopOrderDetail;
                    $product['orderid'] = $orderid;
                    $product['createtime'] = time();
                    $data['ShopOrderDetail'] = $product;
                    if (!$model->add($data)) {
                        throw new \Exception();
                    }
                    ShopCart::deleteAll('productid = :pid' , [':pid' => $product['productid']]);
                    ShopProduct::updateAllCounters(['num' => -$product['productnum']], 'productid = :pid', [':pid' => $product['productid']]);
                }
            }
            $transaction->commit();
        }catch(\Exception $e) {
            $transaction->rollback();
            return $this->redirect(['cart/index']);
        }
        return $this->redirect(['order/check', 'orderid' => $orderid]);
    }
    public function actionConfirm()
    {
        //addressid, expressid, status, amount(orderid,userid)
        try {
            // if (Yii::$app->session['isLogin'] != 1) {
            //     return $this->redirect(['member/auth']);
            // }
            if (!Yii::$app->request->isPost) {
                throw new \Exception();
            }
            $post = Yii::$app->request->post();
            $loginname = Yii::$app->session['loginname'];
            $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one();
            if (empty($usermodel)) {
                throw new \Exception();
            }
            $userid = $usermodel->userid;
            $model = ShopOrder::find()->where('orderid = :oid and userid = :uid', [':oid' => $post['orderid'], ':uid' => $userid])->one();
            if (empty($model)) {
                throw new \Exception();
            }
            $model->scenario = "update";
            $post['status'] = ShopOrder::CHECKORDER;
            $details = ShopOrderDetail::find()->where('orderid = :oid', [':oid' => $post['orderid']])->all();
            $amount = 0;
            foreach($details as $detail) {
                $amount += $detail->productnum*$detail->price;
            }
            if ($amount <= 0) {
                throw new \Exception();
            }
            $express = Yii::$app->params['expressPrice'][$post['expressid']];
            if ($express < 0) {
                throw new \Exception();
            }
            $amount += $express;
            $post['amount'] = $amount;
            $data['ShopOrder'] = $post;
            if (empty($post['addressid'])) {
                return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
            }
            if ($model->load($data) && $model->save()) {
                return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
            }
        }catch(\Exception $e) {
            return $this->redirect(['index/index']);
        }
    }
    public function actionPay()
    {
        try{
            // if (Yii::$app->session['isLogin'] != 1) {
            //     throw new \Exception();
            // }
            $orderid = Yii::$app->request->get('orderid');
            $paymethod = Yii::$app->request->get('paymethod');
            
            if (empty($orderid) || empty($paymethod)) {
                throw new \Exception();
            }
            if ($paymethod == 'alipay') {
                
                return Pay::alipay($orderid);
            }
        }catch(\Exception $e) {}
        // return $this->redirect(['order/index']);
    }
     public function actionGetexpress()
    {
        // $expressno = Yii::$app->request->get('expressno');
        $expressno="600540489887";
        $res = Express::search($expressno);
        var_dump($res);
        die;
        // echo $res;
        // exit;
    }
    
}
