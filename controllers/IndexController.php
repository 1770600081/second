<?php

namespace app\controllers;

use app\controllers\CommonController;
use yii\web\Controller;
// require_once(dirname(dirname(__FILE__))."/qqlogin/install/index.php");

class IndexController extends CommonController
{
	protected $except = ['index'];
    public function actionIndex()
    {
    	// echo "<pre>";
    	// var_dump($_SESSION);
    	// die;
        $this->layout='loser1';
        return $this->render('index');
    }
}
