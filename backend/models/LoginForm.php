<?php
namespace backend\models;
use yii\base\Model;
use yii\web\IdentityInterface;

class LoginForm extends Model{
    public $name;
    public $email;
    public $password;
    public $code;
    public function rules()
    {
        return [
            [['name','password'],'required','message'=>'{attribute}必填'],
            [['email'],'safe'],
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
        ];
    }
    public function login2(IdentityInterface $identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            $id = $identity->getId();
            $ip = Yii::$app->getRequest()->getUserIP();
            if ($this->enableSession) {
                $log = "User '$id' logged in from $ip with duration $duration.";
            } else {
                $log = "User '$id' logged in from $ip. Session not enabled.";
            }
            Yii::info($log, __METHOD__);
            $this->afterLogin($identity, false, $duration);
        }
        return !$this->getIsGuest();
    }
public function login(){
    $admin=Admin::findOne(['name'=>$this->name]);
    if ($admin){
        if(\Yii::$app->security->validatePassword($this->password,$admin->password)){
            \Yii::$app->user->login($admin);
            $admin->last_login_time=time();
            $admin->last_login_ip=$_SERVER["REMOTE_ADDR"];
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