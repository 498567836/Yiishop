<?php
namespace frontend\models;
use yii\base\Model;
use yii\web\IdentityInterface;

class LoginForm extends Model{
    public $username;
    public $password;
    public $code;
    public $remember;
    public $times;
    public static $timesoption=[
            1*60*60=>'1小时',
            4*60*60=>'4小时',
            1*24*60*60=>'1天',
            7*24*60*60=>'7天',
        ];
    public function rules()
    {
        return [
            [['username'],'required','message'=>'{attribute}必填'],
            ['password','required','message'=>'{attribute}必填'],
            [['remember'],'boolean'],
            [['times'],'integer'],
            //验证码验证规则
            ['code','captcha','captchaAction'=>'member/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名：',
            'password'=>'密码：',
            'remember'=>'记住我',
            'times'=>'记住时间',
        ];
    }
public function login(){
    $member=Member::findOne(['username'=>$this->username]);
    if ($member){
        //var_dump($this);exit;
        if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
            \Yii::$app->user->login($member,$this->remember?$this->times:0);
            $member->last_login_time=time();
            $member->last_login_ip=ip2long(\Yii::$app->request->userIP);
            //var_dump($member->last_login_time=time(),$member->last_login_ip);exit;
            $member->save(false);

            //将cookie购物车同步到数据库
            $cookies=\Yii::$app->request->cookies;
            $cart=$cookies->get('cart');
            if ($cart==null){
                $carts=[];
            }else{
                $carts=unserialize($cart->value);
            };
            foreach (array_keys($carts) as $goods_id){
                //已登录
                $member_id=$member->id;
                $model=Cart::find()->where(['=','member_id',$member_id])->andWhere(['=','goods_id',$goods_id])->one();
                if($model){
                    $model->amount+=$carts[$goods_id];
                }else{
                    $model=new Cart();
                    $model->member_id=$member_id;
                    $model->goods_id=$goods_id;
                    $model->amount=$carts[$goods_id];
                }
                $model->save();
            }
            //清除cookie中的cart数据
            $cookies = \Yii::$app->response->cookies;
            $cookies->remove('cart');
            //var_dump($carts);exit;
            return true;
        }else{
            $this->addError('password','密码错误');
        }
    }else{
        //用户不存在,提示 用户不存在 错误信息
        $this->addError('username','用户名不存在');
    }
    return false;
}

}