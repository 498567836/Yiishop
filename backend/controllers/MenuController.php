<?php

namespace backend\controllers;

use backend\models\Menu;
use backend\models\RbacFilter;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=new Menu();
        //var_dump($model->getChildren());exit;
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','菜单添加成功');
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        $model=Menu::findOne($id);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->pid!=0 && Menu::findOne(['pid'=>$id])!=null){
                $model->addError('pid','主分类不能修改到子分类');
            }else{
                $model->save();
                \Yii::$app->session->setFlash('success','菜单修改成功');
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        if(Menu::deleteAll(['id'=>$id])){
            \Yii::$app->session->setFlash('success','菜单删除成功');
        }
        return $this->redirect('index');
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
