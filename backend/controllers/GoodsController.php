<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\RbacFilter;
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
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $fileName='brand/'.date('Ymd').'/'.uniqid().'.'.$fileext;
                    return $fileName;
                },//文件的保存方式
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if ( $goods_id){
                        $goodsgellery=new GoodsGallery();
                        $goodsgellery->goods_id=$goods_id;
                        $goodsgellery->path=$action->getWebUrl();
                        $goodsgellery->save();
                        $action->output['fileUrl']  =$goodsgellery->path;
                        $action->output['id']  =$goodsgellery->id;
                    }else{
                        $action->output['fileUrl']  =$action->getWebUrl();
                    }
                },
            ],
        ];
    }
    public function actionShow($id){
        $model=Goods::findOne($id);
        $photos=GoodsGallery::find()->where(['=','goods_id',$id])->all();
        $goodsintro=GoodsIntro::findOne(['=','goods_id',$id]);
        return $this->render('show',['model'=>$model,'photos' => $photos,'goodsintro'=>$goodsintro]);
    }
    public function actionPhotos($id){
        $photos=GoodsGallery::find()->where(['=','goods_id',$id])->all();
        return $this->render('photos', ['photos' => $photos,'goods_id'=>$id]);
    }
    public function actionDelphoto(){
        //var_dump($_GET['id']);exit;
        //var_dump($_POST['id']);
        $id=\Yii::$app->request->post('id');
        //var_dump($id);exit;
        if(GoodsGallery::deleteAll(['id'=>$id])){
            return 'success';
        }
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
