
<?=\yii\bootstrap\Html::a('添加文章',['article/add'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-10"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['article/index'],['class'=>'btn btn-info btn-sm col-md-1']);?>
    <br/><br/>
    <h1><?=$model->name?></h1>
    <p><?=$articledetail->content?></p>




