<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex($status=1)
    {
        $status=($status!='del')?'status!=-1':'status=-1';
        $query = ArticleCategory::find()->where($status)->orderBy('id DESC')->orderBy('sort DESC');
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
        $model = new ArticleCategory();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }
        }
        $model->status=1;
        $status=\backend\models\ArticleCategory::status_options(false);
        return $this->render('add', ['model' => $model,'status'=>$status]);
    }
    public function actionEdit($id)
    {
        $model = ArticleCategory::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }
        }
        $status=\backend\models\ArticleCategory::status_options(true);
        return $this->render('add', ['model' => $model,'status'=>$status]);
    }
    public function actionDelete($id){
        $model = ArticleCategory::findOne($id);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article-category/index']);
    }


}
