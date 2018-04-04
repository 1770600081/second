<?php

namespace app\models;

use yii\db\ActiveRecord;

class ShopOrderDetail extends ActiveRecord
{
    public function rules()
    {
        return [
            [['productid', 'productnum', 'price', 'orderid', 'createtime'],'required'],
        ];
    }

    public static function tableName()
    {
        return "shop_order_detail";
    }

    public function add($data)
    {
        if ($this->load($data) && $this->save()) {
            return true;
        }
        return false;
    }



}
