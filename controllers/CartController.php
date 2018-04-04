<?php

namespace app\controllers;
use yii;
use app\models\User;
use app\controllers\CommonController;
use app\models\ShopProduct;
use app\models\ShopCart;

class CartController extends CommonController
{   
	public $enableCsrfValidation=false;
    public function actionIndex()
    {
    	$this->layout='loser1';
    	if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where(["username" => Yii::$app->session['loginname']])->one()->userid;
        $cart = ShopCart::find()->where(['userid'=> $userid])->asArray()->all();
        $data = [];
        foreach ($cart as $k=>$pro) {
            $product = ShopProduct::find()->where(['productid' => $pro['productid']])->one();
            $data[$k]['cover'] = $product->cover;
            $data[$k]['title'] = $product->title;
            $data[$k]['productnum'] = $pro['productnum'];
            $data[$k]['price'] = $pro['price'];
            $data[$k]['productid'] = $pro['productid'];
            $data[$k]['cartid'] = $pro['cartid'];
        }
        return $this->render("index", ['data' => $data]);
    }
    public function actionAdd()
    {
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where(["username" => Yii::$app->session['loginname']])->one()->userid;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            echo "<pre>";
            var_dump($post);
            die;
            $num = Yii::$app->request->post()['productnum'];
            $data['ShopCart'] = $post;
            $data['ShopCart']['userid'] = $userid;
        }
        if (Yii::$app->request->isGet) {
            $productid = Yii::$app->request->get("productid");
            $model = ShopProduct::find()->where(["productid"=> $productid])->one();
            $price = $model->issale ? $model->saleprice : $model->price;
            $num = 1;
            $data['ShopCart'] = ['productid' => $productid, 'productnum' => $num, 'price' => $price, 'userid' => $userid];
        }
        if (!$shopcart=ShopCart::find()->where(['productid'=>$productid,'userid'=>$userid])->one()){
            $shopcart = new ShopCart;
        } else {
            $data['ShopCart']['productnum'] = $shopcart->productnum + $num;
        }
        $data['ShopCart']['createtime'] = time();
        // $da['Cart']=$data['ShopCart'];
        // $data=$data['Cart'];
        // echo "<pre>";
        // var_dump($da);
        // die;
        $shopcart->load($data);
        // $shopcart->setAttributes($da['Cart']);
        $shopcart->save();
        // echo "<pre>";
        // var_dump($model->getErrors());
        // die;
        return $this->redirect(['cart/index']);
    }
    public function actionMod()
    {
        $cartid = Yii::$app->request->post("cartid");
        $productnum = Yii::$app->request->post("num");
        ShopCart::updateAll(['productnum' => $productnum], 'cartid = :cid', [':cid' => $cartid]);
    }
    public function actionDel()
    {
        $cartid = Yii::$app->request->get("cartid");
        ShopCart::deleteAll('cartid = :cid', [':cid' => $cartid]);
        return $this->redirect(['cart/index']);
    }
}
