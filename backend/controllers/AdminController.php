<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Admin::find();
        //总条数
        $total = $query->count();
        //每页显示条数 3
        $perPage = 3;
        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Admin();
        $model->scenario = Admin::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->password= \Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = \Yii::$app->security->generateRandomString();
            $model->save(false);
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['admin/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Admin::findOne($id);
        $model->scenario = Admin::SCENARIO_EDIT;
        $model->password=null;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->password){
                $model->password= \Yii::$app->security->generatePasswordHash($model->password);
            }else{
                $model->password=$model->getOldAttribute('password');
            }
            $model->save(false);
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['admin/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEditSelf(){
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }else{
            $model =  \Yii::$app->user->identity;
            $model->scenario = Admin::SCENARIO_EDITSELF;
            $model->password=null;
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                if(\Yii::$app->security->validatePassword($model->oldpassword,$model->getOldAttribute('password'))){
                    if ($model->password){
                        if (\Yii::$app->security->validatePassword($model->password,$model->getOldAttribute('password'))){
                            \Yii::$app->session->setFlash('danger','新密码与旧密码不能一样');
                            return $this->render('add',['model'=>$model]);
                        }
                        $model->password= \Yii::$app->security->generatePasswordHash($model->password);
                    }else{
                        $model->password=$model->getOldAttribute('password');
                    }
                    $model->save(false);
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['admin/index']);
                }else{
                    \Yii::$app->session->setFlash('danger','原密码错误');
                }
            }
            return $this->render('add',['model'=>$model]);
        }

    }
    public function actionDelete($id){
        if(Admin::deleteAll(['id'=>$id])){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('success','删除失败');
        }
        return $this->redirect(['admin/index']);
    }
//登录
    public function actionLogin()
    {
        //1 认证(检查用户的账号和密码是否正确)
        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate() && $model->login()){
            //登录成功
            //var_dump(11111);exit;
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['admin/index']);
        }elseif(\Yii::$app->request->post()){
            \Yii::$app->session->setFlash('danger','登录失败');
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        $model = new LoginForm();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->render('login',['model'=>$model]);
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
    public function behaviors()
    {
        return [
            'ACF'=>[
                'class'=>AccessControl::className(),
                'only'=>['index','add','edit','delete'],//哪些操作需要使用该过滤器
                'rules'=>[
                    [
                        'allow'=>true,//是否允许
                        'actions'=>['index','add','edit','delete'],//指定操作
                        'roles'=>['@'],//指定角色 ?表示未认证用户(未登录) @表示已认证用户(已登录)
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['index'],
                        'roles'=>['?'],
                    ],
                ]
            ]

        ];
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
}
