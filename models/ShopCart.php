<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_cart".
 *
 * @property string $cartid
 * @property string $productid
 * @property string $productnum
 * @property string $price
 * @property string $userid
 * @property string $createtime
 */
class ShopCart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'productnum', 'userid', 'createtime'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cartid' => 'Cartid',
            'productid' => 'Productid',
            'productnum' => 'Productnum',
            'price' => 'Price',
            'userid' => 'Userid',
            'createtime' => 'Createtime',
        ];
    }
}
