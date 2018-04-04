<?php

namespace app\modules\loserbackstage\controllers;
use app\modules\loserbackstage\controllers\CommonController;
use app\models\ShopOrder;
use app\models\ShopOrderDetail;
use app\models\ShopProduct;
use app\models\User;
use app\models\ShopAddress;
use yii\web\Controller;
use yii\data\Pagination;
use Yii;
class OrderController extends CommonController
{
    public function actionList()
    {
        $this->layout = "loser1";
        $model = ShopOrder::find();
        $count = $model->count();
        $pageSize = 10;
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $data = $model->offset($pager->offset)->limit($pager->limit)->all();
        $data = ShopOrder::getDetail($data);
        return $this->render('list', ['pager' => $pager, 'orders' => $data]);
    }

    public function actionDetail()
    {
        $this->layout = "loser1";
        $orderid = (int)Yii::$app->request->get('orderid');
        $order = ShopOrder::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        $data = ShopOrder::getData($order);
        return $this->render('detail', ['order' => $data]);
    }

    public function actionSend()
    {
        $this->layout = "loser1";
        $orderid = (int)Yii::$app->request->get('orderid');
        $model = ShopOrder::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        $model->scenario = "send";
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->status = ShopOrder::SENDED;
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '发货成功');
            }
        }
        return $this->render('send', ['model' => $model]);
    }
    

}







