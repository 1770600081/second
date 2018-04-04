<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
class ShopProduct extends ActiveRecord
{
    const AK = 'toix9okVaTB0uz6oxPe_vTnW-psg62jGuQOb01uZ';
    const SK = '9BbqEK8nmW-LlLWnmt4Aqe3CWWKN-IiSJMDlY0a3';
    const DOMAIN = 'o7zgluxwg.bkt.clouddn.com';
    const BUCKET = 'imooc-shop';

    public $cate;
    public $xsl;
    public function rules()
    {
        return [
            ['title', 'required', 'message' => '标题不能为空','on'=>'add'],
            ['descr', 'required', 'message' => '描述不能为空','on'=>'add'],
            ['cateid', 'required', 'message' => '分类不能为空','on'=>'add'],
            ['price', 'required', 'message' => '单价不能为空','on'=>'add'],                                           
            [['price','saleprice'], 'number', 'min' => 0.01, 'message' => '价格必须是数字','on'=>'add'],
            ['num', 'integer', 'min' => 0, 'message' => '库存必须是数字','on'=>'add'],
            [['issale','ishot', 'pics', 'istui'],'safe','on'=>'add'],
            [['cover'], 'required','on'=>'add'],
            ['xsl', 'file', 'skipOnEmpty' => false,'extensions' => ['txt', 'zip', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', '7z', 'gz'],'checkExtensionByMimeType' => false,'on'=>'shangchuan'],
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
    public function add($data)
    {
        $this->scenario="add";
        if ($this->load($data) && $this->save()) {
            return true;
        }
        return false;
    }
}
