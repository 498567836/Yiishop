<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
<?=\yii\bootstrap\Html::a('添加',['rbac/add-permission'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-10"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['rbac/index-permission'],['class'=>'btn btn-info btn-sm  col-md-1']);?>
<br/><br/>
<table id="table_id_example"  class="table   table-bordered table-condensed table-striped table-hover display " >
    <thead>
    <tr>
        <th>名称/路由</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $value): ?>
        <tr>
            <td><?=$value->name?></td>
            <td><?=$value->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['edit-permission','name'=>$value->name],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['delete-permission','name'=>$value->name],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>

</table>

<?php
Yii::$app->view->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs(new \yii\web\JsExpression(
<<<JS
window.setTimeout(function() {
    $('#w2-success-0').attr('style','display:none');
    $('#w2-danger-0').attr('style','display:none');
},3000);
$(document).ready( function () {
    $('#table_id_example').DataTable();
} );
JS
));
?>
