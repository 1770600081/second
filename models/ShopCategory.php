<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "shop_category".
 *
 * @property string $cateid
 * @property string $title
 * @property string $parentid
 * @property string $createtime
 */
class ShopCategory extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'adminid',
                'updatedByAttribute' => null,
                'value' => Yii::$app->admin->id,
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentid', 'createtime','adminid'], 'integer'],
            [['title'],'required','message'=>'分类名称不能为空'],
            [['title'],'valatetitle'],
        ];
    }
    public function valatetitle(){
        if(!$this->hasErrors()){
            $result=ShopCategory::find()->where(['title'=>$this->title,'parentid'=>$this->parentid])->one();
            if(!empty($result)){
                $this->addError('title','分类名称已存在,请重新添加');
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cateid' => 'Cateid',
            'title' => '分类名称',
            'parentid' => '上级分类',
            'createtime' => 'Createtime',
        ];
    }
    public function add($data){
        $data['ShopCategory']['createtime']=time();
        if($this->load($data)&&$this->save()){
            return true;
        }
        return false;
    }
    public function getData(){
        $cates=ShopCategory::find()->Asarray()->all();
        return $cates;
    }
    public function getTree($cates,$pid=0){
        $tree=[];
        foreach ($cates as $cate) {
                    if ($cate['parentid']==$pid) {
                        $tree[]=$cate;
                        $tree=array_merge($tree,$this->getTree($cates,$cate['cateid']));
                    }
                }
            return $tree;        
    }
    public function getLeave($data, $p = "|-----")
    {
        $tree = [];
        $num = 1;
        $prefix = [0 => 1];
        while($val = current($data)) {
            $key = key($data);
            if ($key > 0) {
                if ($data[$key - 1]['parentid'] != $val['parentid']) {
                    $num ++;
                }
            }
            if (array_key_exists($val['parentid'], $prefix)) {
                $num = $prefix[$val['parentid']];
            }
            $val['title'] = str_repeat($p, $num).$val['title'];
            $prefix[$val['parentid']] = $num;
            $tree[] = $val;
            next($data);
        }
        return $tree;
    }
    public function getall(){
        $data=$this->getData();
        $tree=$this->getTree($data);
        $tree=$this->getLeave($tree);
        $option=['添加顶级分类'];
        foreach ($tree as $key => $value) {
            $option[$value['cateid']]=$value['title'];
        }
        return $option;
    }
    public function getlist($list){
        // $data=$this->getData();
        $tree=$this->getTree($list);
        $tree=$this->getLeave($tree);
        return $tree;
    }
    public static function getMenu()
    {
        $top = self::find()->where(['parentid'  => 0])->limit(11)->orderby('createtime asc')->asArray()->all();
        $data = [];
        foreach((array)$top as $k=>$cate) {
            $cate['children'] = self::find()->where(['parentid'  => $cate['cateid']])->limit(10)->asArray()->all();
            $data[$k] = $cate;
        }
        return $data;
    }
    public function getPrimaryCate()
    {
        $data = self::find()->where("parentid = :pid", [":pid" => 0]);
        if (empty($data)) {
            return [];
        }
        $pages = new \yii\data\Pagination(['totalCount' => $data->count(), 'pageSize' => '2']);
        $data = $data->orderBy('createtime desc')->offset($pages->offset)->limit($pages->limit)->all();
        if (empty($data)) {
            return [];
        }
        $primary = [];
        foreach ($data as $cate) {
            $primary[] = [
                'id' => $cate->cateid,
                'text' => $cate->title,
                'children' => $this->getChild($cate->cateid)
            ];
        }
        return ['data' => $primary, 'pages' => $pages];
    }
    public function getChild($pid)
    {
        $data = self::find()->where('parentid = :pid', [":pid" => $pid])->all();
        if (empty($data)) {
            return [];
        }
        $children = [];
        foreach ($data as $child) {
            $children[] = [
                "id" => $child->cateid,
                "text" => $child->title,
                "children" => $this->getChild($child->cateid)
            ];
        }
        return $children;
    }
}
