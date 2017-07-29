<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RbacFilter;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    public function actionAddPermission(){
        $model=new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //var_dump($model);exit;
            $authManager=\Yii::$app->authManager;
            //创建权限
            $permission=$authManager->createPermission($model->name);
            $permission->description=$model->description;
            //保存到数据表
            $authManager->add($permission);
            \Yii::$app->session->setFlash('success','权限添加成功');
            return $this->redirect(['index-permission']);

        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionIndexPermission()
    {
        //获取所有权限
        $model=\Yii::$app->authManager->getPermissions();
        return $this->render('index-permission',['model'=>$model]);
    }
    public function actionEditPermission($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        if ($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model=new PermissionForm();

        if (\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                $authManager=\Yii::$app->authManager;
                //更新权限
                $permission->description=$model->description;
                //保存到数据表
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['index-permission']);
            }
        }else{
            $model->name=$permission->name;
            $model->description=$permission->description;
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionDeletePermission($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        if($permission)\Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['index-permission']);
    }
public function actionAddRole(){
    $model=new RoleForm();
    $model->scenario = RoleForm::SCENARIO_ADD;
    if($model->load(\Yii::$app->request->post()) && $model->validate()){
        $authManager=\Yii::$app->authManager;
        //创建和保存角色
        $role=$authManager->createRole($model->name);
        $role->description=$model->description;
        $authManager->add($role);
        //给角色赋予权限
        if (is_array($model->permissions)){
            foreach ($model->permissions as $permissionname){
                $permission=$authManager->getPermission($permissionname);
                if($permission) $authManager->addChild($role,$permission);
            }
        }
        \Yii::$app->session->setFlash('success','角色添加成功');
        return $this->redirect(['index-role']);
    }
    return $this->render('add-role',['model'=>$model]);
}
    public function actionIndexRole()
    {
        //获取所有角色
        $model=\Yii::$app->authManager->getRoles();
        return $this->render('index-role',['model'=>$model]);
    }
    public function actionEditRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        $model=new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;
        $model->name=$role->name;
        $model->description=$role->description;
        $permissions=\Yii::$app->authManager->getPermissionsByRole($name);
        $model->permissions=ArrayHelper::map($permissions,'name','name');
        //var_dump($model->permissions);exit;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager=\Yii::$app->authManager;
            //创建和保存角色
            $role=$authManager->getRole($name);
            $role->description=$model->description;
            //$authManager->add($role);////////
            $authManager->update($name, $role);
            //给角色赋予权限
            $authManager->removeChildren($role);
            if (is_array($model->permissions)){
                foreach ($model->permissions as $permissionname){
                    $permission=$authManager->getPermission($permissionname);
                    if($permission) $authManager->addChild($role,$permission);
                }
            }
            \Yii::$app->session->setFlash('success','角色修改成功');
            return $this->redirect(['index-role']);
        }
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDeleteRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if ($role){
            \Yii::$app->authManager->remove($role);
            \Yii::$app->session->setFlash('success','角色删除成功');
        }
        return $this->redirect(['index-role']);

    }
    public function behaviors(){
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
                //'only'=>['add-article','del-article','view-article'],
            ]
        ];
    }



}
