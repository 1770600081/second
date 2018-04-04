<?php

namespace app\modules\loserbackstage\controllers;
use app\modules\loserbackstage\controllers\CommonController;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\db\Query;
use app\modules\loserbackstage\models\Rbac;

class RbacController extends CommonController
{
    public $mustlogin = ['createrole','roles','assignitem','createrule','deleteitem'];

    //创建角色
    public function actionCreaterole()
    {
        if (Yii::$app->request->isPost) {
            $auth = Yii::$app->authManager; //管理权限的几张表
            // $post = Yii::$app->request->post();
            // $auth->createRole($post)也可以写成这种格式把传输过来的值,直接实例化写入变量中 传null相当于实例化auth_item表
            $role = $auth->createRole(null); //管理auth_item表
            echo "<pre>";
            var_dump($role);
            die;
            // echo "<pre>";
            // var_dump($role);
            // die;
            $post = Yii::$app->request->post();
            if (empty($post['name']) || empty($post['description'])) {
                throw new \Exception('参数错误');
            }
            $role->name = $post['name'];
            $role->description = $post['description'];
            $role->ruleName = empty($post['rule_name']) ? null : $post['rule_name'];
            $role->data = empty($post['data']) ? null : $post['data'];
            // $auth->add($role)    进行保存信息
            if ($auth->add($role)) {
                Yii::$app->session->setFlash('info', '添加成功');
            }
        }
        return $this->render('createitem');
    }
    //展示角色或权限
    public function actionRoles()
    {
        $auth = Yii::$app->authManager;
        $data = new ActiveDataProvider([
            'query' => (new Query)->from($auth->itemTable)->where('type = 1')->orderBy('created_at desc'),
            'pagination' => ['pageSize' =>10,'pageSizeParam' => false],
        ]);
        return $this->render('items', ['dataProvider' => $data]);
    }

    public function actionAssignitem($name)
    {
        // $name=Yii::$app->request->get();
        $name = htmlspecialchars($name); //实体化那么 防止注入
        $auth = Yii::$app->authManager;
        $parent = $auth->getRole($name); //当前名字
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            // echo "<pre>";
            // var_dump($post);
            // die;
            // array(2) {
            //   ["_csrf"]=>
            //   string(88) "7g2MD3R4pvIo3c-beBFJm6CvVSFLHlhAJLZoWs-MWOTYRthoOTrQplCR-ew-JTHo5uITexpGbDNm5zpp_MgHtA=="
            //   ["children"]=>
            //   array(4) {
            //     [0]=>
            //     string(12) "普通用户"
            //     [1]=>
            //     string(10) "category/*"
            //     [2]=>
            //     string(8) "common/*"
            //     [3]=>
            //     string(10) "manage/del"
            //   }
            // }
            if (Rbac::addChild($post['children'], $name)) { //把规则添加到auth_item_child
                Yii::$app->session->setFlash('info', '分配成功');
            }
        }

        $children = Rbac::getChildrenByName($name);
        // echo "<pre>";
        // $data=$auth->getRoles();
        // $count=$data->count();
        // var_dump(count($data));
        // die;
        // echo "<pre>";
        // var_dump($children);
        // die;
        $roles = Rbac::getOptions($auth->getRoles(), $parent);  //获取所有的角色
        $permissions = Rbac::getOptions($auth->getPermissions(), $parent);  //获取所有的权限
        return $this->render('assignitem', ['parent' => $name, 'roles' => $roles, 'permissions' => $permissions, 'children' => $children]);
    }
    //添加规则
    public function actionCreaterule()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (empty($post['class_name'])) {
                throw new \Exception('参数错误');
            }
            $className = "app\\models\\". $post['class_name'];
            if (!class_exists($className)) {
                throw new \Exception('规则类不存在');
            }
            $rule = new $className;
            if (Yii::$app->authManager->add($rule)) {
                Yii::$app->session->setFlash('info', '添加成功');
            }
        }
        return $this->render("createrule");
    }
    public function actionDeleteitem($name)
    {
        $name = htmlspecialchars($name); //实体化那么 防止注入
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name); //当前名字
        $auth->remove($role);
        $data = new ActiveDataProvider([
            'query' => (new Query)->from($auth->itemTable)->where('type = 1')->orderBy('created_at desc'),
            'pagination' => ['pageSize' =>10,'pageSizeParam' => false],
        ]);
        return $this->render('items', ['dataProvider' => $data]);

        
    }
}
