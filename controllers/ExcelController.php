<?php

namespace app\controllers;
use Yii;
use app\models\ShopProduct;
use app\controllers\CommonController;
use yii\web\Controller;
// require_once(dirname(dirname(__FILE__))."/qqlogin/install/index.php");

class ExcelController extends CommonController
{
    public function actionBasic()
    {
    	//基本导出操作
    	require_once(Yii::getAlias('@yr')."/excel/PHPExcel.php");//引入文件
    	$objPHPExcel=new \PHPExcel();//实例化PHPExcel类,相当于在桌面上创建excel表格
    	$objSheet=$objPHPExcel->getActiveSheet();//获取当前活动sheet的操作对象
    	$objSheet->setTitle('demo');//给当前的活动sheet设置名称
    	$objSheet->setCellValue("A1","姓名")->setCellValue("B1","分数");//给当前单元格填充数据
    	$objSheet->setCellValue("A2","测试")->setCellValue("B2","100");
    	// $objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");//生成指定格式的excel文件 .xlsx Excel2007 xlsx
    	$objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成指定格式的excel文件.xls Excel5 2003 xls
    	$objWrite->save(Yii::getAlias('@yr')."/excelceshi/dome.xls");  
    }
    public function actionSbasic()
    {
    	//基本导出操作
    	require_once(Yii::getAlias('@yr')."/excel/PHPExcel.php");//引入文件
    	$objPHPExcel=new \PHPExcel();//实例化PHPExcel类,相当于在桌面上创建excel表格
    	//进行查询数据
    		echo "<pre>";
    		$model=ShopProduct::find()->groupBy('title')->asArray()->all();
    		$count=count($model);
    	for($i=0;$i<$count;$i++)
    	{
    		if($i>=1){
    			$objPHPExcel->createSheet();//如果大于一个Sheet需要重新创建一个Sheel
    		}
    		$objPHPExcel->setActiveSheetIndex($i);//把新创建的sheet是定为当前活动sheet
    		$objSheet=$objPHPExcel->getActiveSheet();//获取当前活动sheet的操作对象
    		$objSheet->setTitle("第".$i.'种品牌');//给当前的活动sheet设置名称
    		$objSheet->setCellValue("A1","商品名字")->setCellValue("B1","商品简介")->setCellValue("C1","商品价格")->setCellValue("D1","商品图片");//给当前单元格填充数据
    		$title=$model[$i]['title'];
    		$mo=ShopProduct::find()->where(['title'=>$title])->asArray()->all();
    		$co=count($mo);
    		if($co!=0){
    			for($j=2;$j<($co+2);$j++){
    				$a=0;
	    		$objSheet->setCellValue("A".$j,$mo[$a]['title'])->setCellValue("B".$j,$mo[$a]['descr'])->setCellValue("C".$j,$mo[$a]['price'])->setCellValue("D".$j,$mo[$a]['cover']);
	    		$a++;
	    		}  
    		}
    	}

    	$objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");//生成指定格式的excel文件 .xlsx Excel2007 xlsx
    	// $objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成指定格式的excel文件.xls Excel5 2003 xls
    	$objWrite->save(Yii::getAlias('@yr')."/excelceshi/category_xlsx.xlsx");  
    }
    //在浏览器中输出
    public function actionWbasic()
    {
    	//基本导出操作
    	require_once(Yii::getAlias('@yr')."/excel/PHPExcel.php");//引入文件
    	$objPHPExcel=new \PHPExcel();//实例化PHPExcel类,相当于在桌面上创建excel表格
    	//进行查询数据
    		echo "<pre>";
    		$model=ShopProduct::find()->groupBy('title')->asArray()->all();
    		$count=count($model);
    	for($i=0;$i<$count;$i++)
    	{
    		if($i>=1){
    			$objPHPExcel->createSheet();//如果大于一个Sheet需要重新创建一个Sheel
    		}
    		$objPHPExcel->setActiveSheetIndex($i);//把新创建的sheet是定为当前活动sheet
    		$objSheet=$objPHPExcel->getActiveSheet();//获取当前活动sheet的操作对象
    		$objSheet->setTitle("第".$i.'种品牌');//给当前的活动sheet设置名称
    		$objSheet->setCellValue("A1","商品名字")->setCellValue("B1","商品简介")->setCellValue("C1","商品价格")->setCellValue("D1","商品图片");//给当前单元格填充数据
    		$title=$model[$i]['title'];
    		$mo=ShopProduct::find()->where(['title'=>$title])->asArray()->all();
    		$co=count($mo);
    		if($co!=0){
    			for($j=2;$j<($co+2);$j++){
    				$a=0;
	    		$objSheet->setCellValue("A".$j,$mo[$a]['title'])->setCellValue("B".$j,$mo[$a]['descr'])->setCellValue("C".$j,$mo[$a]['price'])->setCellValue("D".$j,$mo[$a]['cover']);
	    		$a++;
	    		}  
    		}
    	}

    	$objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");//生成指定格式的excel文件 .xlsx Excel2007 xlsx
    	// $objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成指定格式的excel文件.xls Excel5 2003 xls
    	ob_end_clean();//清楚缓冲区
    	// header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出的是07
		header('Content-Disposition: attachment;filename=ceshi.xlsx');//告诉浏览器将输出文件的名称
		header('Cache-Control: max-age=0');//禁止缓存
		$objWrite->save(Yii::getAlias('@yr')."/excelceshi/ceshi.xlsx"); 
		$objWrite->save("php://output");
		die;
    }
    // 导出excel表格制作样式
    public function actionZbasic()
    {
    	//基本导出操作
    	require_once(Yii::getAlias('@yr')."/excel/PHPExcel.php");//引入文件
    	$objPHPExcel=new \PHPExcel();//实例化PHPExcel类,相当于在桌面上创建excel表格
    	//进行查询数据
    		//进行分类
    		$model=ShopProduct::find()->groupBy('cateid')->asArray()->all();
    		$count=count($model);
    		$index=0;
    	for($i=0;$i<$count;$i++)
    	{
    		$objSheet=$objPHPExcel->getActiveSheet();//获取当前活动sheet的操作对象
    		$objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置垂直水平居中
    		$mo=ShopProduct::find()->where(['cateid'=>$model[$i]['cateid']])->groupBy('title')->asArray()->all();
    		$cou=count($mo);
    		for ($d=0; $d <$cou ; $d++) { 
    			$m=ShopProduct::find()->where(['cateid'=>$model[$i]['cateid'],'title'=>$mo[$d]['title']])->asArray()->all();
    			$hang=3;
	    		foreach ($m as $key => $value) {
	    			if($hang>3){
	    				$cx=1;
	    				$categoryo=$this->getCells(($cx-1)*2);
	    				$categoryt=$this->getCells(($cx-1)*2+1);
	    			}
	    			$categoryone=$this->getCells($index*2);
	    			$categorytwo=$this->getCells($index*2+1);
	    			if(isset($cx)){
	    				$objSheet->setCellValue($categoryo.$hang,$value['title'])->setCellValue($categoryt.$hang,$value['price']);//给当前单元格填充数据
	    			}else{
	    				$objSheet->setCellValue($categoryone."1",$value['title']);
	    				$objSheet->setCellValue($categoryone."2","商品名称")->setCellValue($categorytwo."2","商品价格");
	    				$objSheet->setCellValue($categoryone.$hang,$value['title'])->setCellValue($categorytwo.$hang,$value['price']);//给当前单元格填充数据
	    			$index++;
	    			$objSheet->mergeCells($categoryone."1:".$categorytwo."1");//合并单元格
	    			}
	    			unset($cx);
	    			$hang++;
	    			
	    		}
    		}
    	}
    	// $objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");//生成指定格式的excel文件 .xlsx Excel2007 xlsx
    	$objWrite=\PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成指定格式的excel文件.xls Excel5 2003 xls
    	ob_end_clean();//清楚缓冲区
    	header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
    	// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出的是07
		header('Content-Disposition: attachment;filename=yangshi.xls');//告诉浏览器将输出文件的名称
		header('Cache-Control: max-age=0');//禁止缓存
		$objWrite->save(Yii::getAlias('@yr')."/excelceshi/yangshi.xls"); 
		$objWrite->save("php://output");
    }
    public function getCells($a){
    	$arr=range('A','Z');
    	return $arr[$a];
    }
    //导入excel文件到数据库中
    public function actionAllread(){
    	require_once(Yii::getAlias('@yr')."/excel/PHPExcel/IOFactory.php");//引入读取表单的文件文件
    	$filename=Yii::getAlias('@yr')."/excelceshi/ceshi.xls";//读取的文件名字
    	//全部加载文件 加载全部sheet
    	$objPHPExcel=\PHPExcel_IOFactory::load($filename);//加载文件
    	// $sheetCount=$objPHPExcel->getSheetCount();//获取excel文件中存在多少sheet
    	// for ($i=0; $i <$sheetCount ; $i++) { 
   		// 	$data=$objPHPExcel->getSheet($i)->toArray();//读取每个sheet里的数据 全部放入到数组中
   		// 	echo "<pre>";
   		// 	var_dump($data);
   		// 	die;
    	// }这种获取数据文件对服务器压力很大容易奔溃
    	foreach ($objPHPExcel->getWorksheetIterator() as  $sheet) {
    		foreach ($sheet->getRowIterator() as $row) {//循环行
    			if($row->getRowIndex()<2){//不显示第一行数据
    				continue;
    			}
    			foreach ($row->getCellIterator() as $cell) {//循环列
    				$data=$cell->getValue();//获取单元格数据
    				echo "<pre>";
    				var_dump($data);
    				
    			}
    		}
    	}
    }
    //加载部分数据
    public function actionRead(){
    	require_once(Yii::getAlias('@yr')."/excel/PHPExcel/IOFactory.php");//引入读取表单的文件文件
    	$filename=Yii::getAlias('@yr')."/excelceshi/yangshi.xls";//读取的文件名字
    	$fileType=\PHPExcel_IOFactory::identify($filename);//获取文件类型
    	$objReader=\PHPExcel_IOFactory::createReader($fileType);//获取文件的操作对象 可以自动判断文件类型 选择2007 或者 05
    	$sheetName="第0种品牌";
    	$objReader->setLoadSheetsOnly($sheetName);//指定读取某个sheet
    	$objPHPExcel=$objReader->load($filename);//加载文件

    	foreach ($objPHPExcel->getWorksheetIterator() as  $sheet) {//循环sheet

    		foreach ($sheet->getRowIterator() as $row) {//循环行
    			if($row->getRowIndex()<2){//不显示第一行数据
    				continue;
    			}

    			foreach ($row->getCellIterator() as $cell) {//循环列
    				$data=$cell->getValue();//获取单元格数据
    				echo "<pre>";
    				var_dump($data);
    				
    			}
    		}
    	}
    }
}
