<?php

namespace app\modules\loserbackstage\controllers;
use Yii;
use app\models\ShopCategory;
use app\models\ShopProduct;
use yii\data\Pagination;
use crazyfd\qiniu\Qiniu;
use yii\web\UploadedFile;
use app\modules\loserbackstage\controllers\CommonController;
/**
 * Default controller for the `admin` module
 */
class ProductController extends CommonController
{
    public function actionAdd()
    {
        $this->layout = "loser1";
        $model = new ShopProduct;
        $cate = new ShopCategory;
        $list = $cate->getall();
        unset($list[0]);
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $pics = $this->upload();
            if (!$pics) {
                $model->addError('cover', '封面不能为空');
            } else {
                $post['ShopProduct']['cover'] = $pics['cover'];
                $post['ShopProduct']['pics'] = $pics['pics'];
            }
            if ($pics && $model->add($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
                Yii::$app->session->setFlash('info', '添加失败');
            }

        }

        return $this->render("add", ['opts' => $list, 'model' => $model]);
    }
    private function upload()
    {
        if ($_FILES['ShopProduct']['error']['cover'] > 0) {
            return false;
        }
        $qiniu = new Qiniu(ShopProduct::AK, ShopProduct::SK, ShopProduct::DOMAIN, ShopProduct::BUCKET);
        // $key 可以随意更改 唯一值
        $key = uniqid();
        // var_dump($key);
        // die;
        $qiniu->uploadFile($_FILES['ShopProduct']['tmp_name']['cover'], $key);
        $cover = $qiniu->getLink($key);
        $pics = [];
        foreach ($_FILES['ShopProduct']['tmp_name']['pics'] as $k => $file) {
            if ($_FILES['ShopProduct']['error']['pics'][$k] > 0) {
                continue;
            }
            $key = uniqid();
            $qiniu->uploadFile($file, $key);
            $pics[$key] = $qiniu->getLink($key);
        }
        return ['cover' => $cover, 'pics' => json_encode($pics)];
    }
    public function actionList()
    {
        $model = ShopProduct::find();
        $count = $model->count();
        $pageSize = 10;
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        // var_dump($pager);
        // die;
        $products = $model->offset($pager->offset)->limit($pager->limit)->all();
        $this->layout = "loser1";
        
        // var_dump($pager);
        // die;
        return $this->render("products", ['pager' => $pager, 'products' => $products]);
    }
    public function actionMod()
    {
        $this->layout = "loser1";
        $model = new ShopProduct;
        $cate = new ShopCategory;
        $list = $cate->getall();
        unset($list[0]);

        $productid = Yii::$app->request->get("productid");
        $model = ShopProduct::find()->where(['productid' => $productid])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $qiniu = new Qiniu(ShopProduct::AK, ShopProduct::SK, ShopProduct::DOMAIN, ShopProduct::BUCKET);
            $post['ShopProduct']['cover'] = $model->cover;
            if ($_FILES['ShopProduct']['error']['cover'] == 0) {
                $key = uniqid();
                $qiniu->uploadFile($_FILES['ShopProduct']['tmp_name']['cover'], $key);
                $post['ShopProduct']['cover'] = $qiniu->getLink($key);
                // echo "<pre>";
                // var_dump($post['ShopProduct']['cover'],$model->cover);
                // die;
                // echo "<pre>";
                // var_dump(basename($model->cover),$post['ShopProduct']['cover']);
                // die;
                // 七牛中删除上传的图片
                $qiniu->delete(basename($model->cover));

            }
            $pics = [];
            foreach($_FILES['ShopProduct']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['ShopProduct']['error']['pics'][$k] > 0) {
                    continue;
                }
                $key = uniqid();
                $qiniu->uploadfile($file, $key);
                $pics[$key] = $qiniu->getlink($key);
            }
            $post['ShopProduct']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');

            }

        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);

    }
    public function actionRemovepic(){
        $get=Yii::$app->request->get();
        $productid=$get['productid'];
        $key=$get['key'];
        echo "<pre>";
        $check=ShopProduct::find()->where(['productid'=>$productid])->one();
        // var_dump($check);
        // die;
        $qiniu = new Qiniu(ShopProduct::AK, ShopProduct::SK, ShopProduct::DOMAIN, ShopProduct::BUCKET);
                // 七牛中删除上传的图片
                $qiniu->delete($key);
                $pics=json_decode($check->pics,true);
                unset($pics[$key]);
                $check->pics = json_encode($pics);
                $check->save();
                return $this->redirect(['product/mod', 'productid' => $productid]);
    }
    public function actionDel()
    {
        $productid = Yii::$app->request->get("productid");
        $model = ShopProduct::find()->where(['productid'=>$productid])->one();
        $key = basename($model->cover);
        $qiniu = new Qiniu(ShopProduct::AK, ShopProduct::SK, ShopProduct::DOMAIN, ShopProduct::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        foreach($pics as $key=>$file) {
            $qiniu->delete($key);
        }
        ShopProduct::deleteAll(['productid' =>$productid]);
        return $this->redirect(['product/list']);
    }

    public function actionOn()
    {
        $productid = Yii::$app->request->get("productid");
        ShopProduct::updateAll(['ison' => '1'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
    public function actionOff()
    {
        $productid = Yii::$app->request->get("productid");
        ShopProduct::updateAll(['ison' => '0'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
    public function actionLead()
    {
        $this->layout="loser1";
        $model=new ShopProduct();
        if(Yii::$app->request->isPost){
            $model->xsl = UploadedFile::getInstance($model, 'xsl');
            $model->scenario="shangchuan";
            // if($model->validate()){
            //     $model->xsl->saveAs('uploads/' . $model->xsl->baseName . '.' . $model->xsl->extension);
            // }
            $address='uploads/' . $model->xsl->baseName . '.' . $model->xsl->extension;
            require_once(Yii::getAlias('@yr')."/excel/PHPExcel.php");
            require_once(Yii::getAlias('@yr')."/excel/PHPExcel/IOFactory.php");
            require_once(Yii::getAlias('@yr')."/excel/PHPExcel/Reader/Excel5.php");
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007'); 
            $file=Yii::getAlias('@yr')."/web/".$address;
            if(!$objReader->canRead($file)){
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            }
            $objPHPExcel = $objReader->load($file,$encode='utf-8');
            $sheet = $objPHPExcel->getSheet(0); 
            $highestRow = $sheet->getHighestRow();           //取得总行数 
            $highestColumn = $sheet->getHighestColumn();     //取得总列数
            for($j=2;$j<=$highestRow;$j++)                        //从第二行开始读取数据
            { 
            $str="";
                for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
                 { 
                     $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'|*|';//读取单元格
                 } 
            $str=mb_convert_encoding($str,'GBK','auto');//根据自己编码修改
            $strs = explode("|*|",$str);
            $model=new ShopProduct();
            $model->cateid=$strs[1];
            $model->title=$strs[2];
            $model->descr=$strs[3];
            $model->num=$strs[4];
            $model->price=$strs[5];
            $model->cover=$strs[6];
            $model->pics=$strs[7];
            $model->issale=$strs[8];
            $model->ishot=$strs[9];
            $model->istui=$strs[10];
            $model->saleprice=$strs[11];
            $model->ison=$strs[12];
            $model->createtime=$strs[13];
                if($model->save()){
                    Yii::$app->session->setFlash('info','添加成功请查看商品');
                }
            }
        }
        return $this->render('lead',[
            'model'=>$model
        ]);
    }
}
