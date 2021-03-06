<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;

class MemberController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
public function actionRegister(){
        $model=new Member();
    if($model->load(\Yii::$app->request->post()) && $model->validate()){
        //验证短信验证码
        $code2 = \Yii::$app->session->get('code_'.$model->tel);
        if($model->tel_code == $code2){
            $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = \Yii::$app->security->generateRandomString();
            //var_dump($model->getErrors());exit;
            $model->save(false);
            return $this->redirect(['member/login']);
        }else{
            $model->addError('tel_code','短信验证码错误');
        }
    }
        //\Yii::$app->layout=false;
        return $this->render('register',['model'=>$model]);
}
//登录
    public function actionLogin()
    {
        //1 认证(检查用户的账号和密码是否正确)
        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate() && $model->login()){
            //登录成功
            return $this->redirect(['/goods/index']);
        }elseif(\Yii::$app->request->post()){
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        $model = new LoginForm();
        return $this->redirect('login');
    }
    public function actionUser()
    {
        //可以通过 Yii::$app->user 获得一个 User实例，
        $user = \Yii::$app->user;
        //var_dump($user);

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        var_dump($identity);

        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
        var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($isGuest);
    }
//定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
//                'class'=>'yii\captcha\CaptchaAction',
                'class'=>CaptchaAction::className(),
                'minLength'=>4,
                'maxLength'=>4,
                //'height'=>	100,
                //'backColor'=>'red',
                //'foreColor'=>'red',
                //'fontFile'=>'微软雅黑'
//                height:			高度
//                backColor:		背景色
//                foreColor			文字颜色
//                minLength		最小长度（文字字数）
//                maxLength		最大长度
//                fontFile			字体文件
            ]
        ];
    }
    public function actionAddress(){
        if (!\Yii::$app->user->isGuest){
            $user_id=\Yii::$app->user->id;
            $model=new Address();
            $modelall=Address::find()->where(['=','user_id',$user_id])->orderBy('status DESC')->all();
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                $model->user_id= $user_id;
                //var_dump($model->status);exit;
                $model->save();
                //var_dump($model->getErrors());exit;
                return $this->redirect(['/member/address']);
            }
            return $this->render('address',['model'=>$model,'modelall'=>$modelall]);
        }
        return $this->redirect(['/member/login']);
    }
    public function actionEditAddress($id){
        $model=Address::findOne($id);
        $modelall=Address::find()->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->user_id= \Yii::$app->user->id;
            //var_dump($model->status);exit;
            $model->save();
            //var_dump($model->getErrors());exit;
            $this->redirect('address');
        }
        return $this->render('address',['model'=>$model,'modelall'=>$modelall]);
    }
    public function actionDeleteAddress($id){
        if(Address::deleteAll(['id'=>$id])){
            $this->redirect('address');
        }
    }
    public function actionDefaultAddress($id){
        $model=Address::findOne($id);
        if($model){
            Address::updateAll(['status'=>0]);
            $model->status=1;
            $model->save();
            $this->redirect('address');
        }
    }
    //发送短信功能
    public function actionSendSms()
    {
        //var_dump($_POST['tel']);exit;
        $code = rand(10000,99999);
        //$tel = '18328661534';
        $res = \Yii::$app->sms->setPhoneNumbers($_POST['tel'])->setTemplateParam(['name'=>$code])->send();
        //var_dump($res);exit;
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$_POST['tel'],$code);
    }

}
