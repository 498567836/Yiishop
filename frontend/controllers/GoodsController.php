<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class GoodsController extends Controller
{
    public $layout=false;
     public function actionIndex(){
         $model=GoodsCategory::find()->where(['=','parent_id','0'])->all();
         //var_dump($model);exit;
         return $this->render('index',['model'=>$model]);
     }
    public function actionList($id){
         $pid=[];
        $pid_0=GoodsCategory::find()->select('id')->where(['=','parent_id',$id])->all();
        if(empty($pid_0)){
            $pid[]=$id;
        }else{
            foreach ($pid_0 as $id_0){
                $pid[]=$id_0->id;
                $pid_1=GoodsCategory::find()->select('id')->where(['=','parent_id',$id_0->id])->all();
                foreach ($pid_1 as $id_1){
                    $pid[]=$id_1->id;
                }
            }
        }
        //var_dump($pid);exit;
        $model=Goods::find()->where(['in','goods_category_id',$pid])->all();
        return $this->render('list',['model'=>$model]);
    }
    public function actionShow($id){
        $model=Goods::findOne(['=','id',$id]);
        $goods_intro=GoodsIntro::findOne(['=','goods_id',$id]);
        $goods_gallery=GoodsGallery::find()->where(['=','goods_id',$id])->all();
        //var_dump($goods_gallery[0]->path);exit;
        return $this->render('show',['model'=>$model,'goods_intro'=>$goods_intro,'goods_gallery'=>$goods_gallery]);
    }
}