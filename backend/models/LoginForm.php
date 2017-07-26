<?php
namespace backend\models;
use yii\base\Model;
use yii\web\IdentityInterface;

class LoginForm extends Model{
    public $name;
    public $email;
    public $password;
    public $code;
    public $remember;
    public function rules()
    {
        return [
            [['name'],'required','message'=>'{attribute}必填'],
            ['password','required','message'=>'{attribute}必填'],
            [['email'],'email'],
            [['remember'],'boolean'],
            //验证码验证规则
            ['code','captcha','captchaAction'=>'admin/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'用户名',
            'password'=>'密码',
            'email'=>'邮箱',
            'remember'=>'记住我',
        ];
    }
public function login(){
    $admin=Admin::findOne(['name'=>$this->name]);
    if ($admin){
        if(\Yii::$app->security->validatePassword($this->password,$admin->password)){
            \Yii::$app->user->login($admin,$this->remember?60*10:0);
            $admin->last_login_time=time();
            //$admin->last_login_ip=$_SERVER["REMOTE_ADDR"];
            $admin->last_login_ip=ip2long(\Yii::$app->request->userIP);
            //var_dump($admin->last_login_time=time(),$admin->last_login_ip);exit;
            $admin->save(false);
            return true;
        }else{
            $this->addError('password','密码错误');
        }
    }else{
        //用户不存在,提示 用户不存在 错误信息
        $this->addError('name','用户名不存在');
    }
    return false;
}

}