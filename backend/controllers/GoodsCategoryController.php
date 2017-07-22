<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsCategoryQuery;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=GoodsCategory::find()->select(['id','name','parent_id','intro'])->orderBy('id DESC');
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
//        foreach ($model as &$a){
//            //$parent_name=GoodsCategory::find()->select(['name'])->where(['id'=>$a->parent_id]);
//            $a->parent_name=GoodsCategory::findOne($a->parent_id);
//        }
//        var_dump($model);exit;
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new GoodsCategory();
        $model->parent_id=0;
        $categories=GoodsCategory::find()->select(['id','name','parent_id','intro'])->asArray()->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->parent_id==0){
                $model->makeRoot();
            }else{
                $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($category);
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

    public function actionEdit($id){
        $model=GoodsCategory::findOne($id);
        $categories=GoodsCategory::find()->select(['id','name','parent_id','intro'])->asArray()->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->parent_id==0){
                $model->makeRoot();
            }else{
                $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($category);
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods-category/index']);
        }
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionDelete($id){
            $children=GoodsCategory::findOne(['parent_id'=>$id]);
            //var_dump($children);
        if ($children){
            \Yii::$app->session->setFlash('danger','有子类不能删除');
        }else{
            GoodsCategory::deleteAll(['id'=>$id]);
            \Yii::$app->session->setFlash('success','删除成功');
        }
        return $this->redirect(['goods-category/index']);
    }



}
