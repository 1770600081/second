<?php

namespace app\modules\loserbackstage\controllers;
use Yii;
use yii\web\Controller;
use app\models\ShopCategory;
use app\models\ShopProduct;
use yii\web\UploadedFile;

/**
 * Default controller for the `admin` module
 */
class ProductController extends Controller
{
    public function actionAdd()
    {
        $this->layout = "loser1";
        $model = new ShopProduct;
        $cate = new ShopCategory;
        $list = $cate->getall();
        unset($list[0]);
        //单图片上传
        // if (Yii::$app->request->isPost) {
        //     $post = Yii::$app->request->post();
        //     $model->cover = UploadedFile::getInstance($model, 'cover');
        //     if ($model->upload()) {
        //     文件上传成功
        //     echo   '<img width="800px" src="uploads/图片权限.png">';
        //     die;
        //     }
        //     echo "<pre>";
        //     var_dump($model->cover);
        //     die;
        // }
        // 多图片上传
        // if (Yii::$app->request->isPost) {
        //     $post = Yii::$app->request->post();
        //     // $model->cover = UploadedFile::getInstance($model, 'cover');
        //     $model->pics = UploadedFile::getInstances($model, 'pics');
        //     if ($model->upload()) {
        //     // 文件上传成功
        //     // echo   '<img width="800px" src="uploads/图片权限.png">';
        //     // die;
        //     var_dump(1111111111);
        //     die;
        //     }
        //     // echo "<pre>";
        //     // var_dump($model->cover);
        //     // die;
        // }
        //文件上传
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->cover = UploadedFile::getInstance($model, 'cover');
            if ($model->upload()) {
            // 文件上传成功
            echo   '<img width="800px" src="uploads/图片权限.png">';
            die;
            }
            echo "<pre>";
            var_dump($model->cover);
            die;
        }

        return $this->render("add", ['opts' => $list, 'model' => $model]);
    }
    
    
}
