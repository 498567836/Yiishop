<?php

namespace backend\controllers;

use backend\models\Brand;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;

class BrandController extends \yii\web\Controller
{
    public function actionIndex($status=1)
    {
        $status=($status!='del')?'status!=-1':'status=-1';
        $query = Brand::find()->where($status)->orderBy('id DESC')->orderBy('sort DESC');
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

    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
//                $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
                //var_dump($model->logoFile);exit;
//                if ($model->logoFile) {
//                    $dir = \Yii::getAlias('@webroot') . '/upload/brand/' . date('Ymd');
//                    if (!is_dir($dir)) {
//                        mkdir($dir, 0777, true);
//                    }
//                    $fileName='/upload/brand/' . date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
//                    $model->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                    $model->logo=$fileName;
//                }else{}
                $model->save(false);
//                $file=explode("/", $model->logo)[count(explode("/", $model->logo))-1];
//                //var_dump($file);exit;
//                if(empty($file)){unlink('upload/'.$file);}
                return $this->redirect(['brand/index']);
            }
        }
            $model->status=1;
            $status=\backend\models\Brand::status_options(false);
            //var_dump($status);exit;
            return $this->render('add', ['model' => $model,'status'=>$status]);
    }

    public function actionEdit($id)
    {
        $model = Brand::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
                //var_dump($model->logoFile);exit;
                if ($model->logoFile) {
                    if ($model->logo){
                        //strstr('abc@jb51.net', '@', TRUE); //参数设定true, 返回查找值@之前的首部，abc
                        //strstr($model->logo,'.',true);
                        $fileName=strstr($model->logo,'.',true).'.'.$model->logoFile->extension;
                    }else{
                        $dir = \Yii::getAlias('@webroot') . '/upload/brand/' . date('Ymd');
                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $fileName='/upload/brand/' . date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
                    }
                    $model->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo=$fileName;
                }else{}
                $model->save(false);
                return $this->redirect(['brand/index']);
            }
        }
        $status=\backend\models\Brand::status_options(true);
        return $this->render('add', ['model' => $model,'status'=>$status]);
    }
    public function actionDelete($id){
        $model = Brand::findOne($id);
        $model->status=-1;
        $model->save();
        return $this->redirect(['brand/index']);
    }

    public function actions() {
        return [
            's-upload' => [
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
//测试七牛云文件上传
    public function actionQiniu()
    {
        $config = [
            'accessKey'=>'FPa1ieS6XVUr8U2gu6UPgE-bQJBx8QdYIE79rYn0',
            'secretKey'=>'F1Mg8ShzvpzZOnWgYgbPNJ-UcvSvPdaBkIG09_bl',
            'domain'=>'http://otbnpn31t.bkt.clouddn.com/',
            'bucket'=>'yii2shop',
            'area'=>Qiniu::AREA_HUANAN
        ];
        $qiniu = new Qiniu($config);
        $key = 'upload/2e/79/2e795418fcb72341d801d1fa70ca6fabc33444cb.png';
        //将图片上传到七牛云
        $qiniu->uploadFile(
            \Yii::getAlias('@webroot').'/upload/2e/79/2e795418fcb72341d801d1fa70ca6fabc33444cb.png',
            $key);
        //获取该图片在七牛云的地址
        $url = $qiniu->getLink($key);
        var_dump($url);
    }



}
