<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use Yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RbacController extends Controller
{
    public function actionIndex()
    {
    	$transaction = Yii::$app->db->beginTransaction();
    	try {
        $dir=dirname(dirname(__FILE__)).'/modules/loserbackstage/controllers';
        $wenjian=glob($dir.'/*');//取出当前目录下的所有文件
        unset($wenjian['6']);
        foreach ($wenjian as $value) {
        	$file=file_get_contents($value);	//把一个文件里的内容写入到字符串中
        	preg_match('#class ([a-zA-Z]+)Controller#',$file,$kzqname);
        	$kzqname=$kzqname[1];
        	$permissions[] = strtolower($kzqname. '/*');	//把字符串转换为小写
        	preg_match_all('#action([a-zA-Z_]+)#', $file, $matches);
            foreach ($matches[1] as $aName) {
                $permissions[] = strtolower($kzqname. '/'. $aName);
            }
            $auth = Yii::$app->authManager;
            foreach ($permissions as $permission) {
                if (!$auth->getPermission($permission)) {	//判断是否存在改方法
                    $obj = $auth->createPermission($permission);	//把方法存入到auth_item表中
                    $obj->description = $permission;
                    $auth->add($obj);
                	}
            	}
        	}
        	$transaction->commit();
            echo "import success \n";
    	}catch(\Exception $e) {
            $transaction->rollback();
            echo "import failed \n";
        }
    }
}
