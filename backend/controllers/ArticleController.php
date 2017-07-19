<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Articledetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex($status=1)
    {
        $status=($status!='del')?'status!=-1':'status=-1';
        $query = Article::find()->where($status)->orderBy('id DESC')->orderBy('sort DESC');
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
        $model = new Article();
        $articledetail=new Articledetail();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $articledetail->load($request->post());
            if ($model->validate() && $articledetail->validate()) {
                $model->create_time=time();
                $model->save();
                $articledetail->article_id=$model->id;
                $articledetail->save();
                return $this->redirect(['article/index']);
            }
        }
        $model->status=1;
        $status=\backend\models\ArticleCategory::status_options(false);
        return $this->render('add', ['model' => $model,'status'=>$status,'articledetail'=>$articledetail]);
    }
    public function actionEdit($id)
    {
        $model = Article::findOne($id);
        $articledetail=Articledetail::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $articledetail->load($request->post());
            if ($model->validate() && $articledetail->validate()) {
                $model->create_time=time();
                $model->save();
                $articledetail->article_id=$model->id;
                $articledetail->save();
                return $this->redirect(['article/index']);
            }
        }
        $model->status=1;
        $status=\backend\models\ArticleCategory::status_options(false);
        return $this->render('add', ['model' => $model,'status'=>$status,'articledetail'=>$articledetail]);
    }
    public function actionShow($id){
        $model = Article::findOne($id);
        $articledetail=Articledetail::findOne($id);
        return $this->render('show', ['model' => $model,'articledetail'=>$articledetail]);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }


}
