<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Brand::find()->orderBy('id DESC');
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
                $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
                //var_dump($model->logoFile);exit;
                if ($model->logoFile) {
                    $dir = \Yii::getAlias('@webroot') . '/upload/brand/' . date('Ymd');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $fileName='/upload/brand/' . date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
                    $model->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo=$fileName;
                }else{}
                $model->save(false);
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





}
