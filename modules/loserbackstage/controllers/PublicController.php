<?php

namespace app\modules\loserbackstage\controllers;
use Yii;
use yii\web\Controller;
use app\modules\loserbackstage\models\ShopAdmin;
/**
 * Default controller for the `loserbackstage` module
 */
class PublicController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
    	$this->layout=false;
    	$model=new ShopAdmin();
    	if(Yii::$app->request->post()){
            $post= Yii::$app->request->post();
            if($model->login($post)){
                $this->redirect(['default/index']);
                //终止语句的作用
                Yii::$app->end();
            }
        }
        return $this->render('login',[
        	'model'=>$model
        ]);
    }
    public function actionLoginout()
    {
        // Yii::$app->session->removeAll();
        // if(!Yii::$app->session['admin']){
        //     return $this->redirect(['public/login']);
        // }
        Yii::$app->admin->logout(false);
        return $this->redirect(['public/login']);
    }
    public function actionSeekpassword()
    {
        $this->layout=false;
        $model= new ShopAdmin();
        if(Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            if($model->seekPass($post)){
                Yii::$app->session->setFlash('info','电子邮件发送成功请查收');
            }
        }
        return    $this->render('seekpassword',[
            'model'=>$model
        ]);
    }
}
