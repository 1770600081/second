<?php

namespace app\controllers;

use app\controllers\CommonController;
use Yii;
use app\models\ShopProduct;
use yii\data\Pagination;

class ProductController extends CommonController
{   
    public $layout=false;
    public function actionIndex()
    {
        $this->layout="loser2";
        $model=ShopProduct::find();
        $count=$model->count();
        $pageSize=2;
        $pager=new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize,'pageSizeParam' => false,'validatePage' => false]);
        $totalpage=ceil($count / $pageSize);
        $all=$model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        $tui = ShopProduct::find()->where(['istui'=>'1'])->asArray()->orderby('createtime desc')->limit(5)->all();
        $hot = ShopProduct::find()->where(['ishot'=>'1'])->asArray()->orderby('createtime desc')->limit(5)->all();
        $sale= ShopProduct::find()->where(['issale'=>'1'])->asArray()->orderby('createtime desc')->limit(5)->all();
        return $this->render("index", ['sale' => $sale, 'tui' => $tui, 'hot' => $hot, 'all' => $all, 'pager' => $pager, 'count' => $count
            ,'totalpage'=>$totalpage
        ]);
    }
    public function actionDetail()
    {
    	$this->layout="loser2";
        $productid = Yii::$app->request->get("productid");
        $product = ShopProduct::find()->where(['productid' => $productid])->asArray()->one();
        $data['all'] = ShopProduct::find()->where(['ison' => "1"])->orderby('createtime desc')->limit(7)->all();
        return $this->render("detail", ['product' => $product, 'data' => $data]);
    }
}
