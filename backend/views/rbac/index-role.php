<?=\yii\bootstrap\Html::a('添加',['rbac/add-role'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-10"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['rbac/index-role'],['class'=>'btn btn-info btn-sm  col-md-1']);?>
<br/><br/>
<table class="table   table-bordered table-condensed table-striped table-hover " >
    <tr>
        <th>角色</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $value): ?>
        <tr>
            <td><?=$value->name?></td>
            <td><?=$value->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['edit-role','name'=>$value->name],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['delete-role','name'=>$value->name],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$this->registerJs(new \yii\web\JsExpression(
<<<JS
window.setTimeout(function() {
    $('#w2-success-0').attr('style','display:none');
    $('#w2-danger-0').attr('style','display:none');
},3000);
JS
));
