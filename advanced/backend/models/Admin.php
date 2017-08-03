<?php
//namespace backend\models;
//use yii\db\ActiveRecord;
//use Yii;
//
//class Admin extends ActiveRecord
//{
//    public $rememberMe = true;
//    public $repass;
//    public static function tableName()
//    {
//        return "{{%admin}}";
//    }
//
//    public function attributeLabels()
//    {
//        return [
//            'adminuser' => '管理员账号',
//            'adminemail' => '管理员邮箱',
//            'adminpass' => '管理员密码',
//            'repass' => '确认密码',
//        ];
//    }
//
//    public function rules()
//    {
//        return [
//            ['adminuser', 'required', 'message' => '管理员账号不能为空', 'on' => ['login', 'seekpass', 'changepass', 'adminadd', 'changeemail']],
//            ['adminpass', 'required', 'message' => '管理员密码不能为空', 'on' => ['login', 'changepass', 'adminadd', 'changeemail']],
//            ['rememberMe', 'boolean', 'on' => 'login'],
//            ['adminpass', 'validatePass', 'on' => ['login', 'changeemail']],
//            ['adminemail', 'required', 'message' => '电子邮箱不能为空', 'on' => ['seekpass', 'adminadd', 'changeemail']],
//            ['adminemail', 'email', 'message' => '电子邮箱格式不正确', 'on' => ['seekpass', 'adminadd', 'changeemail']],
//            ['adminemail', 'unique', 'message' => '电子邮箱已被注册', 'on' => ['adminadd', 'changeemail']],
//            ['adminuser', 'unique', 'message' => '管理员已被注册', 'on' => 'adminadd'],
//            ['adminemail', 'validateEmail', 'on' => 'seekpass'],
//            ['repass', 'required', 'message' => '确认密码不能为空', 'on' => ['changepass', 'adminadd']],
//            ['repass', 'compare', 'compareAttribute' => 'adminpass', 'message' => '两次密码输入不一致', 'on' => ['changepass', 'adminadd']],
//        ];
//    }
//
//    public function validatePass()
//    {
//        if (!$this->hasErrors()) {
//            $data = self::find()->where('adminuser = :user and adminpass = :pass', [":user" => $this->adminuser, ":pass" => md5($this->adminpass)])->one();
//            if (is_null($data)) {
//                $this->addError("adminpass", "用户名或者密码错误");
//            }
//        }
//    }
//
//    public function validateEmail()
//    {
//        if (!$this->hasErrors()) {
//            $data = self::find()->where('adminuser = :user and adminemail = :email', [':user' => $this->adminuser, ':email' => $this->adminemail])->one();
//            if (is_null($data)) {
//                $this->addError("adminemail", "管理员电子邮箱不匹配");
//            }
//        }
//    }
//
//    public function login($data)
//    {
//        $this->scenario = "login";
//        if ($this->load($data) && $this->validate()) {
//            $lifetime = $this->rememberMe ? 24*3600 : 0;
//            $session = Yii::$app->session;
//            session_set_cookie_params($lifetime);
//            $session['admin'] = [
//                'adminuser' => $this->adminuser,
//                'isLogin' => 1,
//            ];
//            $this->updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)], 'adminuser = :user', [':user' => $this->adminuser]);
//            return (bool)$session['admin']['isLogin'];
//        }
//        return false;
//    }
//
//    public function seekPass($data)
//    {
//        $this->scenario = "seekpass";
//        if ($this->load($data) && $this->validate()) {
//            $time = time();
//            $token = $this->createToken($data['Admin']['adminuser'], $time);
//            $mailer = Yii::$app->mailer->compose('seekpass', ['adminuser' => $data['Admin']['adminuser'], 'time' => $time, 'token' => $token]);
//            $mailer->setFrom("2251586313@qq.com");
//            $mailer->setTo($data['Admin']['adminemail']);
//            $mailer->setSubject("找回密码");
//            if ($mailer->send()) {
//                return true;
//            }
//        }
//        return false;
//
//    }
//
//    public function createToken($adminuser, $time)
//    {
//        return md5(md5($adminuser).base64_encode(Yii::$app->request->userIP).md5($time));
//    }
//
//    public function changePass($data)
//    {
//        $this->scenario = "changepass";
//        if ($this->load($data) && $this->validate()) {
//            return (bool)$this->updateAll(['adminpass' => md5($this->adminpass)], 'adminuser = :user', [':user' => $this->adminuser]);
//        }
//        return false;
//    }
//
//
//    public function reg($data)
//    {
//        $this->scenario = 'adminadd';
//        if ($this->load($data) && $this->validate()) {
//            $this->adminpass = md5($this->adminpass);
//            if ($this->save(false)) {
//                return true;
//            }
//            return false;
//        }
//        return false;
//    }
//
//    public function changeEmail($data)
//    {
//        $this->scenario = "changeemail";
//        if ($this->load($data) && $this->validate()) {
//            return (bool)$this->updateAll(['adminemail' => $this->adminemail], 'adminuser = :user', [':user' => $this->adminuser]);
//        }
//        return false;
//    }
//
//
//
//}
namespace backend\models;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
class Admin extends \yii\db\ActiveRecord implements IdentityInterface{
    public $password;//保存密码的明文
    //定义场景
    public static $statusOptions=[0=>'离线',1=>'在线'];
    public $roles=[];
    const SCENARIO_ADD='add';

//    const SCENARIO_LOGIN='login';
//    const SCENARIO_REGISTER='register';
//    public function scenarios(){
//        $senarios=[
//            self::SCENARIO_LOGIN=>['username','password_hash'],
//            self::SCENARIO_REGISTER=>['username','password_hash','email'],
//        ];
//        $scenarios2=parent::scenarios();
//        return ArrayHelper::merge($senarios,$scenarios2);
    public static function tableName()
    {
      return 'admin';
    }
    public function rules(){
        return [
            [['username','email'],'required','message'=>'输入不能为空'],
            ['password','required','on'=>self::SCENARIO_ADD,'message'=>'输入不能为空'],
            ['password','string','length'=>[3,32],'tooShort'=>'密码太短了'],
            [['status','created_at','updated_at','last_login_time'],'integer'],
            [['username','password_hash','password_reset_token','email'],'string','max'=>255],
            [['auth_key'],'string','max'=>32],
            [['last_login_ip'],'string','max'=>15],
            ['username','unique'],
            [['email'],'unique'],
            [['email'],'email'],
            [['password_reset_token'],'unique'],
            ['roles','safe']

//            [['username','password_hash','email'],'required'],
//            [['status','created_at','updated_at','last_login_time'],'integer'],
//            [['username','password_hash','password_reset_token','email'],'string','max'=>255],
//            [['auth_key'],'string','max'=>32],
//            [['last_login_ip'],'string','max'=>15],
//            [['email'],'unique'],
//            [['password_reset_token'],'unique'],
        ];
    }
    public function attributeLabels(){
        return [
            'id'=>'ID',
            'username'=>'UserName',
            'auth_key'=>'Auth Key',
            'password_hash'=>'Password Hash',
            'password_reset_token'=>'Password Reset Token',
            'email'=>'Email',
            'status'=>'Status',
            'created_at'=>'Created At',
            'updated_at'=>'Updated At',
            'last_login_time'=>'Last Login Time',
            'last_login_ip'=>'Last Login Ip',
            'roles'=>'角色/身份'
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at=time();
            $this->status=1;
            $this->last_login_ip=Yii::$app->request->userIP;
            //生成随机字符串
            $this->auth_key=Yii::$app->security->generateRandomString();
        }
        if($this->password){
            $this->password_hash=yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id){
        return self::findOne(['id'=>$id]);
    }
    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null){

    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId(){
        return $this->id;
    }
    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey(){
        return $this->auth_key;
    }
    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey){
        return $this->auth_key==$authKey;
    }
}