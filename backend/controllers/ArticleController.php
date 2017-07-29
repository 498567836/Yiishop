<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Articledetail;
use backend\models\RbacFilter;
use backend\models\Search;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex($status=1)
    {
        $search=new Search();
        //$search=new Search($this::className());
        //var_dump($search->name);
        $request = new Request();
        if ($request->isPost) {
            $search->load($request->post());
            var_dump($search->search);exit;
        }
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
        return $this->render('index',['model'=>$model,'pager'=>$pager,'search'=>$search]);
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
                \Yii::$app->session->setFlash('success','添加成功');
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
                \Yii::$app->session->setFlash('success','修改成功');
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
    public function actionDelete($id){
        $model = Article::findOne($id);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
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
