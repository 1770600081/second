<?php
namespace app\models;

use yii\rbac\Rule;// execute

use Yii;

class AuthorRule extends Rule
{
    
    public $name = "isAuthor";
    // $user, $item, $params  当前用户的id 角色 一些参数
    public function execute($user, $item, $params)
    {
        $action = Yii::$app->controller->action->id;
        if ($action == 'del') {
            $userid = Yii::$app->request->get("userid");
            $cate = User::findOne($userid);
            return $cate->adminid == $user;
        }
        return true;
    }
}
