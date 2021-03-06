<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_address".
 *
 * @property string $addressid
 * @property string $firstname
 * @property string $lastname
 * @property string $company
 * @property string $address
 * @property string $postcode
 * @property string $email
 * @property string $telephone
 * @property string $userid
 * @property string $createtime
 */
class ShopAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address'], 'string'],
            [['userid', 'createtime'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 32],
            [['company'], 'string', 'max' => 100],
            [['email'],'email','message'=>'电子邮箱格式不正确'],
            [['postcode'], 'string', 'max' => 6],
            [['telephone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'addressid' => 'Addressid',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'company' => 'Company',
            'address' => 'Address',
            'postcode' => 'Postcode',
            'email' => 'Email',
            'telephone' => 'Telephone',
            'userid' => 'Userid',
            'createtime' => 'Createtime',
        ];
    }
}
