<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\data\Pagination;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex($status=1)
    {
        $status=($status!='del')?'status=1':'status=0';
        if((isset($_GET['keywords']) && !empty($_GET['keywords']))  || (isset($_GET['minprice']) && !empty($_GET['minprice'])) || (isset($_GET['maxprice']) && !empty($_GET['maxprice']))){
            //var_dump($_GET['keywords']);exit;
            $keywords=$_GET['keywords'];
            $status.=" && (name like  '%$keywords%' or  sn  like  '%$keywords%' )";
            $maxprice=$_GET['maxprice'];
            $minprice=$_GET['minprice'];
            if(!empty($_GET['minprice']) && !empty($_GET['maxprice'])){
                if ($minprice=='<100'){
                    $status.=" && (shop_price <100)";
                }elseif ($maxprice=='>10000'){
                    $status.=" && (shop_price >=$minprice)";
                }elseif ($maxprice>=$minprice){
                    $status.=" && ( shop_price >=$minprice && $maxprice>=shop_price)";
                }else{
                    $status.=" && (shop_price >=$minprice)";
                }
            }elseif (!empty($_GET['minprice'])){
                if ($minprice=='<100'){
                    $status.=" && (shop_price <100)";
                }else{
                    $status.=" && (shop_price >=$minprice)";
                }
            }elseif (!empty($_GET['maxprice'])){
                if ($maxprice=='>10000'){
                    $status.=" && (shop_price >10000)";
                }else {
                    $status .= " && (shop_price <=$maxprice)";
                }
            }
            //var_dump($status);exit;
        }
        $query = Goods::find()->where($status)->orderBy('id DESC')->orderBy('sort DESC');
        //总条数
        $total = $query->count();
        //每页显示条数 3
        $perPage = 5;
        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }

    public function actionAdd()
    {
        $model = new Goods();
        $goodsintro =new GoodsIntro();
        $categories=GoodsCategory::find()->select(['id','name','parent_id'])->asArray()->all();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $goodsintro->load($request->post());
            if ($model->validate() && $goodsintro->validate()) {
                $model->create_time=time();
                $date=GoodsDayCount::findOne(['day'=>date('Y-m-d',time())]);
                if ($date){
                    $model->sn=date('Ymd',time()).sprintf("%04d", $date->count+1);
                }else{
                    $model->sn=date('Ymd',time()).sprintf("%04d", 1);
                }
//                var_dump($model->sn);
//                var_dump(1111);exit;
                $model->save(false);
                $goodsintro->goods_id=$model->id;
                $goodsintro->save();
                if ($date){
                    $date->count++;
                }else{
                    $date=new GoodsDayCount();
                    $date->day=date('Y-m-d',time());
                    $date->count=1;
                }
                $date->save();
                //var_dump($date->count,$date->day);exit;
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }
        }
        $model->status=1;
        $model->is_on_sale=1;
        return $this->render('add', ['model' => $model,'goodsintro'=>$goodsintro,'categories'=>$categories]);
    }

    public function actionEdit($id)
    {
        $model = Goods::findOne($id);
        $goodsintro =GoodsIntro::findOne($id);
        $categories=GoodsCategory::find()->select(['id','name','parent_id'])->asArray()->all();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $goodsintro->load($request->post());
            if ($model->validate() && $goodsintro->validate()) {
                $model->save(false);
                $goodsintro->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('add', ['model' => $model,'goodsintro'=>$goodsintro,'categories'=>$categories]);

    }
    public function actionDelete($id){
        $model = Goods::findOne($id);
        if ($model){
            $model->status=0;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('danger','删除失败');
        }
        return $this->redirect(['goods/index']);
    }

    public function actions() {
        return [
            'upload' => [//百度编辑器
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            's-upload' => [//uploadifive图片上传
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
                     $fileext = $action->uploadfile->getExtension();
                     $filename = sha1_file($action->uploadfile->tempName);
                     return "{$filename}.{$fileext}";
                 },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
//                    $filehash = sha1(uniqid() . time());
//                    $p1 = substr($filehash, 0, 2);
//                    $p2 = substr($filehash, 2, 2);
//                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                    $fileName='brand/'.date('Ymd').'/'.uniqid().'.'.$fileext;
                    return $fileName;
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //$action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']  = $url;
                },
            ],
        ];
    }


}
