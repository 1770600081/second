<?php

namespace app\models;

use yii\db\ActiveRecord;

class Profile extends ActiveRecord
{
    public static function tableName()
    {
        return "shop_profile";
    }
}
