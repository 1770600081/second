<?php

namespace app\modules\loserbackstage\controllers;
use yii\web\Controller;
use Yii;

class CommonController extends Controller
{
	protected $actions = ['*'];
    protected $except = [];
    protected $mustlogin = [];
    protected $verbs = [];
    public $layout = 'loser1';
    public function behaviors()
    {
        return [
        	//access进行访问过滤
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'user' => 'admin',	//指定组件名称 使用config/web.php/admin 如果不写执定默认的config/web.php/user
                'only' => $this->actions,
                'except' => $this->except,
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                        'roles' => ['?'], // guest
                    ],
                    [
                        'allow' => true,
                        'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                        'roles' => ['@'],
                    ],
                ],
            ],
            //verbs验证是什么方法提交
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => $this->verbs,
            ],
        ];
    }
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $controller = $action->controller->id;
        $actionName = $action->id;
        if (Yii::$app->admin->can($controller. '/*')) {
            return true;
        }
        if (Yii::$app->admin->can($controller. '/'. $actionName)) {
            return true;
        }
        throw new \yii\web\UnauthorizedHttpException('对不起，您没有访问'. $controller. '/'. $actionName. '的权限');
        // return true;
    }
    public function init()
    {
        // if (Yii::$app->session['admin']['isLogin'] != 1) {
        //     return $this->redirect(['/loserbackstage/public/login']);
        // }
    }
}
