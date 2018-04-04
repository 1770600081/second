<?php

namespace app\modules\loserbackstage\controllers;
use app\modules\loserbackstage\controllers\CommonController;
/**
 * Default controller for the `loserbackstage` module
 */
class DefaultController extends CommonController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    protected $mustlogin = ['index'];
    public function actionIndex()
    {
    	$this->layout="loser1";
        return $this->render('index');
    }
}
