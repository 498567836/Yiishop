<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class GoodsController extends Controller
{
     public function actionIndex(){
         $model=GoodsCategory::find()->where(['=','parent_id','0'])->all();
         //var_dump($model);exit;
         return $this->render('index',['model'=>$model]);
     }
    public function actionList($id){
        $model=Goods::find()->where(['=','goods_category_id',$id])->all();
        return $this->render('list',['model'=>$model]);
    }
    public function actionShow($id){
        $model=Goods::findOne(['=','id',$id]);
        $goods_intro=GoodsIntro::findOne(['=','goods_id',$id]);
        $goods_gallery=GoodsGallery::find()->where(['=','goods_id',$id])->all();
        return $this->render('show',['model'=>$model,'goods_intro'=>$goods_intro,'goods_gallery'=>$goods_gallery]);
    }
}