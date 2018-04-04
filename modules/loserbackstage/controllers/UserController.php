<?php

namespace app\modules\loserbackstage\controllers;
use yii\data\Pagination;
use app\models\User;
use app\models\Profile;
use yii\db\Exception;
use Yii;
use app\modules\loserbackstage\controllers\CommonController;

class UserController extends CommonController
{   
    public function actionUsers()
    {
        //关联表shop_user和shop_profile
        $model = User::find()->joinWith('profile');
        $count = $model->count();
        $pageSize = 20;
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $users = $model->offset($pager->offset)->limit($pager->limit)->all();
        $this->layout = "loser1";
        return $this->render('users', ['users' => $users, 'pager' => $pager]);
    }

    public function actionReg()
    {
        $this->layout = "loser1";
        $model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->reg($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            }
        }
        $model->userpass = '';
        $model->repass = '';
        return $this->render("reg", ['model' => $model]);
    }

    public function actionDel()
    {
        try{
            $userid = (int)Yii::$app->request->get('userid');
            if (empty($userid)) {
                throw new Exception();
            }
            $trans = Yii::$app->db->beginTransaction();
            if ($obj = Profile::find()->where(['userid'=>$userid])->one()) {
                $res = Profile::deleteAll(['userid'=>$userid]);
                if (empty($res)) {
                    throw new Exception();
                }
            }
            if (!User::deleteAll(['userid'=>$userid])) {
                throw new Exception();
            }
            $trans->commit();
        } catch(Exception $e) {
                $trans->rollback();
        }
        $this->redirect(['user/users']);
    }

}
