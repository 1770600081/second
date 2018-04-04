<?php


namespace app\modules\loserbackstage\models;
use yii\db\ActiveRecord;
use Yii;

class Rbac extends ActiveRecord 
{
    public static function getOptions($data, $parent)
    {
        $return = [];
        foreach ($data as $obj) {
            if (!empty($parent) && $parent->name != $obj->name && Yii::$app->authManager->canAddChild($parent, $obj)) {
                $return[$obj->name] = $obj->description;
            }
            if (is_null($parent)) {
                $return[$obj->name] = $obj->description;
            }
        }
        return $return;
    }

    public static function addChild($children, $name)
    {
        $auth = Yii::$app->authManager;
        $itemObj = $auth->getRole($name);
        if (empty($itemObj)) {
            return false;
        }
        $trans = Yii::$app->db->beginTransaction();
        try {
            $auth->removeChildren($itemObj); //移除auth_item_child表中某个角色的所有权限
            foreach ($children as $item) {
                // echo "<pre>";
                // var_dump($children);
                // array(4) {
                //   [0]=>
                //   string(12) "普通用户"
                //   [1]=>
                //   string(10) "category/*"
                //   [2]=>
                //   string(8) "common/*"
                //   [3]=>
                //   string(10) "manage/del"
                // }
                // die;
                    // $auth->getRole($item) 
                    // $auth->getPermission($item)
                $obj = empty($auth->getRole($item)) ? $auth->getPermission($item) : $auth->getRole($item);
                $auth->addChild($itemObj, $obj);    //往auth_item_child表中添加信息
            }
            $trans->commit();
        } catch(\Exception $e) {
            $trans->rollback();
            return false;
        }
        return true;
    }

    public static function getChildrenByName($name)
    {
        if (empty($name)) {
            return [];
        }
        $return = [];
        $return['roles'] = [];
        $return['permissions'] = [];
        $auth = Yii::$app->authManager;
        $children = $auth->getChildren($name);  
        if (empty($children)) {
            return [];
        }
        foreach ($children as $obj) {
            if ($obj->type == 1) {
                $return['roles'][] = $obj->name;
            } else {
                $return['permissions'][] = $obj->name;
            }
        }
        return $return;
    }

    public static function grant($adminid, $children)
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($adminid);
            foreach ($children as $item) {
                //通过便利查询出的是名字 还要获取当前的对象才能存存储到表中
                $obj = empty($auth->getRole($item)) ? $auth->getPermission($item) : $auth->getRole($item);
                // $obj是一个对象             
                $auth->assign($obj, $adminid);
            }
            $trans->commit();
        } catch (\Exception $e) {
            $trans->rollback();
            return false;
        }
        return true;
    }

    private static function _getItemByUser($adminid, $type)
    {
        $func = 'getPermissionsByUser';
        if ($type == 1) {
            $func = 'getRolesByUser';
        }
        $data = [];
        $auth = Yii::$app->authManager;
        $items = $auth->$func($adminid);
        foreach ($items as $item) {
            $data[] = $item->name;
        }
        return $data;
    }

    public static function getChildrenByUser($adminid)
    {
        $return = [];
        $return['roles'] = self::_getItemByUser($adminid, 1);
        $return['permissions'] = self::_getItemByUser($adminid, 2);
        return $return;
    }


}
