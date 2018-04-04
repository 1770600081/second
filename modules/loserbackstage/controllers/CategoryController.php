<?php
namespace app\modules\loserbackstage\controllers;
use Yii;
use app\modules\loserbackstage\models\ShopAdmin;
use app\models\ShopCategory;
use yii\data\Pagination;
use yii\db\Exception;
use app\modules\loserbackstage\controllers\CommonController;
use yii\web\Response;
/**
 * Default controller for the `admin` module
 */
class CategoryController extends CommonController
{
   public function actionTree()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new ShopCategory;
        $data = $model->getPrimaryCate();
        
        if (!empty($data)) {

            return $data['data'];
        }
        return [];
    }
    public function actionList()
    {
        $this->layout = "loser1";
        $page = (int)Yii::$app->request->get("page") ? (int)Yii::$app->request->get("page") : 1;
        $perpage = (int)Yii::$app->request->get("per-page") ? (int)Yii::$app->request->get("per-page") : 2;
        $model = new ShopCategory;
        //$cates = $model->getTreeList();
        $data = $model->getPrimaryCate();
        return $this->render("cates", ['pager' => $data['pages'], "page" => $page, "perpage" => $perpage]);
    }
    public function actionAdd()
    {
        $model=new ShopCategory();
        if(Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            if($model->add($post)){
                Yii::$app->session->setFlash('info','添加成功');
            }
        }
        $list=$model->getall();
        $model->title = '';
        $this->layout="loser1";
        return $this->render('add',[
            'model'=>$model,
            'list'=>$list
        ]);
    }
    public function actionMod(){
        $this->layout="loser1";
        $cateid=Yii::$app->request->get("cateid");
        $model=ShopCategory::find()->where(['cateid'=>$cateid])->one();
        if(Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            if($model->load($post)&&$model->save()){
                Yii::$app->session->setFlash('info','修改成功');
            }
        }
        $m=new ShopCategory();
        $list=$m->getall();
        return $this->render('add',[
            'model'=>$model,
            'list'=>$list
        ]);
    }
    public function actionDel(){
        $this->layout="loser1";
        $cateid=Yii::$app->request->get("cateid");
        try {
        $model=ShopCategory::find()->where(['cateid'=>$cateid])->one();
            if(empty($model)){
                throw new Exception('参数错误');
            }
            $all=ShopCategory::find()->where(['parentid'=>$cateid])->all();
            if(!empty($all)){
                throw new Exception('该分类下有子类');
            }
            if(!ShopCategory::deleteAll(['cateid'=>$cateid])){
                throw new Exception("删除失败");
            }
        } catch (Exception $e) {
                Yii::$app->session->setFlash('info',$e->getMessage());
        }
        return $this->redirect(['category/list']);
    }
    
}
