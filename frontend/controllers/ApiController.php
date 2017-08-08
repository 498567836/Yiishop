<?php
namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller {
    //接口开发必须关闭
    public $enableCsrfValidation = false;
    public function init(){
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
    public function actionRegister(){//会员注册
        if (\Yii::$app->request->isPost){
            $model=new Member();
            $model->username=\Yii::$app->request->post('username');
            $model->password_hash=\Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('password'));
            $model->auth_key = \Yii::$app->security->generateRandomString();
            $model->email=\Yii::$app->request->post('email');
            $model->tel=\Yii::$app->request->post('tel');
            if ($model->validate()){
                $model->save();
                $result = [
                    'errorCode'=>0,
                    'errorMsg'=>'注册成功',
                    'data'=>[],
                ];
            }else{
                //验证不通过
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'注册失败，请检测错误信息',
                    'data'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'errorCode'=>99,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
    public function actionLogin(){//会员登录
        if (\Yii::$app->request->isPost){
            $username=\Yii::$app->request->post('username');
            $model=Member::findOne(['username'=>$username]);
            if($model && \Yii::$app->security->validatePassword(\Yii::$app->request->post('password'),$model->password_hash)){
                $model->last_login_time=time();
                $model->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $model->save(false);
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'登录成功',
                    'data'=>[],
                ];
            }else{
                //验证不通过
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'登录失败，用户名或密码输入错误',
                    'data'=>[],
                ];
            }
        }else{
            $result = [
                'errorCode'=>99,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
    public function actionEditPassword(){//修改密码
        if (\Yii::$app->request->isPost){
            $username=\Yii::$app->request->post('username');
            $model=Member::findOne(['username'=>$username]);
            if($model && \Yii::$app->security->validatePassword(\Yii::$app->request->post('oldpassword'),$model->password_hash)){
                $model->password_hash=\Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('password'));
                $model->save(false);
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'修改密码成功',
                    'data'=>[],
                ];
            }else{
                //验证不通过
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'修改失败，用户名或原密码输入错误',
                    'data'=>[],
                ];
            }
        }else{
            $result = [
                'errorCode'=>99,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
    public function actionUserInfo($user_id){//获取当前登录的用户信息
        $member_id=isset($user_id)?$user_id-0:0;
        $user=Member::findOne(['id'=>$member_id]);
            if(!$user){
                $result = [
                    'errorCode'=>9,
                    'errorMsg'=>'获取失败',
                    'data'=>[]
                ];
            }else{
                $result = [
                    'errorCode'=>0,
                    'errorMsg'=>'获取成功',
                    'data'=>$user,
                ];
            }
        return $result;
    }
    public function actionAddAddress(){//添加地址
        if (\Yii::$app->request->isPost){
            $model=new Address();
            $model->username=\Yii::$app->request->post('username');
            $model->tel=\Yii::$app->request->post('tel');
            $model->user_id=\Yii::$app->request->post('user_id');
            $model->province=\Yii::$app->request->post('province');
            $model->city=\Yii::$app->request->post('city');
            $model->zone=\Yii::$app->request->post('zone');
            $model->address=\Yii::$app->request->post('address');
            $model->status=\Yii::$app->request->post('status');
            if ($model->validate()){
                $model->save();
                $result = [
                    'errorCode'=>0,
                    'errorMsg'=>'添加成功',
                    'data'=>[],
                ];
            }else{
                //验证不通过
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'添加失败，请检测错误信息',
                    'data'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'errorCode'=>99,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
    public function actionEditAddress(){//修改地址
        if (\Yii::$app->request->isPost){
            $address_id=\Yii::$app->request->post('address_id');
            $model=Address::findOne(['id'=>$address_id]);
            $model->username=\Yii::$app->request->post('username');
            $model->tel=\Yii::$app->request->post('tel');
            $model->province=\Yii::$app->request->post('province');
            $model->city=\Yii::$app->request->post('city');
            $model->zone=\Yii::$app->request->post('zone');
            $model->address=\Yii::$app->request->post('address');
            $model->status=\Yii::$app->request->post('status');
            if ($model->validate()){
                $model->save();
                $result = [
                    'errorCode'=>0,
                    'errorMsg'=>'修改成功',
                    'data'=>[],
                ];
            }else{
                //验证不通过
                $result = [
                    'errorCode'=>10,
                    'errorMsg'=>'修改失败，请检测错误信息',
                    'data'=>$model->getErrors()
                ];
            }
        }else{
            $address_id=\Yii::$app->request->get('address_id');
            $model=Address::findOne(['id'=>$address_id]);
            $result = [
                'errorCode'=>1,
                'errorMsg'=>'获取地址成功',
                'data'=>$model
            ];
        }
        return $result;
    }
    public function actionDeleteAddress(){//删除地址
        
    }

}