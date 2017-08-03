<?php
namespace backend\models;
use yii\base\Model;
/**
 * Login form
 */
class LoginForm extends Model{
    public $username;
    public $password;
    public $rememberMe = true;
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            // username and password are both required
            [['username', 'password'], 'required','message'=>'输入不能为空'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住我'
        ];
    }
    //用户登录
    public function login(){
        //1根据用户名查找用户
        $admin=Admin::findOne(['username'=>$this->username]);
        if($admin){
            //2验证密码
            if(\yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //3登录和自动登录
                $duration=$this->rememberMe?7*24*3600:0;
                \yii::$app->user->login($admin,$duration);
                return true;
            }else{
                $this->addError('password','用户名或密码不正确');
            }
        }else{
            $this->addError('password','用户名或密码不正确');
        }
        return false;
    }
}