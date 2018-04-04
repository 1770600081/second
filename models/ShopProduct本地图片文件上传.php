<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
class ShopProduct extends ActiveRecord
{
    

    public $cate;
    public function rules()
    {
        return [
            // 单图片验证
            // [['cover'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            // 多图片验证    maxFiles:最多上传4个
            // [['pics'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
            // 文件上传
            [['cover'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cateid' => '分类名称',
            'title'  => '商品名称',
            'descr'  => '商品描述',
            'price'  => '商品价格',
            'ishot'  => '是否热卖',
            'issale' => '是否促销',
            'saleprice' => '促销价格',
            'num'    => '库存',
            'cover'  => '图片封面',
            'pics'   => '商品图片',
            'ison'   => '是否上架',
            'istui'   => '是否推荐',
        ];
    }

    public static function tableName()
    {
        return "shop_product";
    }
    //单图片
    // public function upload()
    // {
        
    //     if ($this->validate()) {
    //         $this->cover->saveAs('uploads/' . $this->cover->baseName . '.' . $this->cover->extension);
    //         // var_dump('uploads/' . $this->cover->baseName . '.' . $this->cover->extension);
    //         // die;
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    // 多图片
    // public function upload()
    // {
    //     if ($this->validate()) { 
    //         foreach ($this->pics as $file) {
    //             $file->saveAs('uploads/'.'111' . $file->baseName . '.' . $file->extension);
    //         }
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    // 文件上传
    public function upload()
    {
        // echo "<pre>";
        // var_dump($this->cover);
        // die;
    
        if ($this->validate()) {
            
            $this->cover->saveAs('uploads/' . $this->cover->baseName . '.' . 'xls');
            var_dump("通过");
            die;
            // var_dump('uploads/' . $this->cover->baseName . '.' . $this->cover->extension);
            // die;
            return true;
        } else {
            var_dump($this->getErrors());
            die;
            return false;
        }
    }



}
