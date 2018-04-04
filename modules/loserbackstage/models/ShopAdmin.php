<?php

namespace app\modules\loserbackstage\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "shop_admin".
 *
 * @property string $adminid 主键ID
 * @property string $adminuser 管理员账号
 * @property string $adminpass 管理员密码
 * @property string $adminemail 管理员电子邮箱
 * @property string $logintime 登录时间
 * @property string $loginip 登录IP
 * @property string $createtime 创建时间
 */
class ShopAdmin extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $rememberMe = true;
    public $repass;
    public static function tableName()
    {
        return 'shop_admin';
    }
    public function attributeLabels()
    {
        return [
            'adminuser' => '管理员账号',
            'adminemail' => '管理员邮箱',
            'adminpass' => '管理员密码',
            'repass' => '确认密码',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['adminuser','required','message'=>'管理员账号不能为空','on'=>['login','seekPass','changePass','adminadd', 'changeemail']],
            ['adminpass','required','message'=>'管理员密码不能为空','on'=>['login','changePass','adminadd', 'changeemail']],
            ['rememberMe','boolean','on'=>'login'],
            ['adminpass','validatePass','on'=>['login', 'changeemail']],
            ['adminemail','required','message'=>'电子邮箱不能为空','on'=>['seekPass','adminadd', 'changeemail']],
            ['adminemail','email','message'=>'电子邮箱格式不正确','on'=>['seekPass','adminadd', 'changeemail']],
            ['adminemail','unique','message'=>'电子邮箱已被注册','on'=>['adminadd', 'changeemail']],
            ['adminuser', 'unique', 'message' => '管理员已被注册', 'on' => 'adminadd'],
            ['adminemail','validateEmail','on'=>'seekPass'],
            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['changePass','adminadd']],
            ['repass', 'compare', 'compareAttribute' => 'adminpass', 'message' => '两次密码输入不一致', 'on' => ['changePass','adminadd']]
        ];
    }
    public function validatePass(){
        //查看之前是否报错
        if(!$this->hasErrors()){
            $data=self::find()->where(['adminuser'=>$this->adminuser])->one();
            if(is_null($data)){
                $this->addError('adminpass','用户名或密码错误');
                return;
            }

            //第一个参数是输入的密码   第二个参数是数据库中查询出的密码
            if (!Yii::$app->getSecurity()->validatePassword($this->adminpass, $data->adminpass))
            {
                $this->addError("adminpass", "用户名或者密码错误");
            }
        }
    }
    public function validateEmail(){
        if(!$this->hasErrors()){
            $data=self::find()->where(['adminuser'=>$this->adminuser,'adminemail'=>$this->adminemail])->one();
            if(is_null($data)){
                $this->addError('adminemail','账号和电子邮箱不匹配');
            }
        }
    }
    public function getAdmin()
    {
        return self::find()->where('adminuser = :user', [':user' => $this->adminuser])->one();
    }
    public function login($data){
        $this->scenario="login";
        if($this->load($data)&&$this->validate()){
            return Yii::$app->admin->login($this->getAdmin(),$this->rememberMe?24*3600:0);
            //设置有效期
            // $lifettime=$this->rememberMe?24*3600:0;
            //设置session里的变量
            // $session = Yii::$app->session;
            //设置session的有效时间
            // session_set_cookie_params($lifettime);
            // $session['admin']=[
                // 'adminuser'=>$this->adminuser,
                // 'isLogin'=>1,
            // ];
            
            // $this->updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)], 'adminuser = :user', [':user' => $this->adminuser]);
            // return (bool)$session['admin']['isLogin'];
        }
        return false;
    }


    public function seekPass($data){
        $this->scenario="seekPass";
        if($this->load($data)&&$this->validate()){
            $time=time();
            $token = $this->createToken($this->adminuser, $time);
            $mailer=Yii::$app->mailer->compose('seekpass',['time'=>$time,'adminuser'=>$this->adminuser,'token' => $token]);
            $mailer->setFrom('fy1770600081@163.com');
            $mailer->setTo($this->adminemail);
            $mailer->setSubject("慕课商城-找回密码");
            if($mailer->send()){
                return true;
            }
        }
        return false;
    }

    
    public function createToken($adminuser, $time)
    {
        return md5(md5($adminuser).base64_encode(Yii::$app->request->userIP).md5($time));
    }
    public function changePass($data){
        $this->scenario='changePass';
        if($this->load($data)&&$this->validate()){
            $result=ShopAdmin::find()->where(['adminuser'=>$data['ShopAdmin']['adminuser']])->one();
            $result->adminpass=md5($data['ShopAdmin']['adminpass']);
            return $result->save();
        }
        return false;
    }
    public function reg($data){
        $this->scenario='adminadd';
        if ($this->load($data) && $this->validate()) {
            // $this->adminpass = md5($this->adminpass);
            $this->adminpass = Yii::$app->getSecurity()->generatePasswordHash($this->adminpass);
            //运行save（）方法时候save（）也会调用验证方法，save（false）时候不会再次进行验证
            if ($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }
     public function changeemail($data)
    {
        $this->scenario = "changeemail";
        if ($this->load($data) && $this->validate()) {
            return (bool)$this->updateAll(['adminemail' => $this->adminemail], 'adminuser = :user', [':user' => $this->adminuser]);
        }
        return false;
    }
    //通过id查取用户信息
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    //通过token查取用户信息
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    //获取用户id
    public function getId()
    {
        return $this->adminid;
    }
    //客户端cookie存储的密钥
    public function getAuthKey()
    {
        return '';
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
    
}
