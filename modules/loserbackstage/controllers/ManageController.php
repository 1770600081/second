<?php

namespace app\modules\loserbackstage\controllers;
use Yii;
use yii\web\Controller;
use app\modules\loserbackstage\models\ShopAdmin;
use yii\data\Pagination;
use app\modules\loserbackstage\models\Rbac;
use app\modules\loserbackstage\controllers\CommonController;
/**
 * Default controller for the `admin` module
 */
class ManageController extends CommonController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionMailchangepass()
    {

        $this->layout=false;
        $model= new ShopAdmin();
        $time=Yii::$app->request->get("timestamp");
        $adminuser=Yii::$app->request->get("adminuser");
        $token=Yii::$app->request->get("token");
        $mytoken=$model->createToken($adminuser,$time);
        if($token!=$mytoken){
            Yii::$app->session->setFlash('mail','token验证失败');
            return  $this->redirect(['public/login']);
        }

        if(time()-$time>300){
            Yii::$app->session->setFlash('mail','修改密码时间不能超过5分钟');
            return  $this->redirect(['public/login']);
        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changePass($post)) {
                Yii::$app->session->setFlash('info', '密码修改成功');
            }
        }

        $model->adminuser=$adminuser;
        return $this->render('mailchangepass',['model'=>$model]);
    }
    public function actionManagers(){
        $this->layout="loser1";
        //加上where只是查询条数不是查询数据数据
        // $model = ShopAdmin::find()->where('static'=>1)
        $model = ShopAdmin::find();
        $count = $model->count();
        // $pageSize = Yii::$app->params['pageSize']['manage'];
        $pageSize=10;
        //总共条数和页面大小
        
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        //重model中查询数据 $pager->offset代表多少也 $pager->limit一页显示的条数
        $managers = $model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('managers',[
            'managers'=>$managers,
            'pager'=>$pager
        ]);
    }
    public function actionReg()
    {

        $this->layout = 'loser1';
        $model = new ShopAdmin();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->reg($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
                Yii::$app->session->setFlash('info', '添加失败');
            }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('reg', ['model' => $model]);
    }
    public function actionDel(){
        $this->layout="loser1";
        $adminid = (int)Yii::$app->request->get("adminid");
        if (empty($adminid) || $adminid == 1) {
            Yii::$app->session->setFlash('info', '删除失败');
            $this->redirect(['manage/managers']);
            return false;
        }
        $result=ShopAdmin::find()->where(['adminid'=>$adminid])->one();
        if ($result->delete()) {
            Yii::$app->session->setFlash('info', '删除成功');
            $this->redirect(['manage/managers']);
        }
    }
    public function actionChangeemail()
    {
        $this->layout = 'loser1';
        $model=ShopAdmin::find()->where(['adminuser'=>Yii::$app->session['admin']['adminuser']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changeemail($post)) {
                Yii::$app->session->setFlash('info', '修改成功');
            }
        }
        $model->adminpass = "";
        return $this->render('changeemail', ['model' => $model]);
    }
     public function actionChangepass()
    {
        $this->layout = "loser1";
        $model=ShopAdmin::find()->where(['adminuser'=>Yii::$app->session['admin']['adminuser']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changepass($post)) {
                Yii::$app->session->setFlash('info', '修改成功');
            }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('changepass', ['model' => $model]);
    }
    public function actionAssign(){
        $adminid=Yii::$app->request->get('adminid');
        $admin=ShopAdmin::find()->where(['adminid'=>$adminid])->one();
        if(empty($admin)){
            throw new \yii\web\NotFoundHttpException('admin not found');  
        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $children = !empty($post['children']) ? $post['children'] : [];
            if (Rbac::grant($adminid, $children)) {
                Yii::$app->session->setFlash('info', '授权成功');
            }
        }
        // 获取角色和权限
        $auth=Yii::$app->authManager;
        $roles = Rbac::getOptions($auth->getRoles(), null);
        $permissions = Rbac::getOptions($auth->getPermissions(), null);
        $children = Rbac::getChildrenByUser($adminid);  //获取当前管理员所拥有的角色和权限
        return $this->render('assign', ['children' => $children, 'roles' => $roles, 'permissions' => $permissions, 'admin' => $admin->adminuser]);
    }
}
