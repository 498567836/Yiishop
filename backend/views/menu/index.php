<?=\yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-10"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['menu/index'],['class'=>'btn btn-info btn-sm  col-md-1']);?>
    <br/><br/>
    <table class="table   table-bordered table-condensed table-striped table-hover " >
        <tr>
            <th>序号</th>
            <th>名称</th>
            <th>权限</th>
            <th>操作</th>
        </tr>
        <?php foreach (\backend\models\Menu::getParents() as $value): ?>
            <tr>
                <td><?=$value->id?></td>
                <td><?=($value->pid!=0?'——':'').$value->name?></td>
                <td><?=$value->url?$value->url:'\\'?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$value->id],['class'=>'btn btn-info btn-sm'])?>
                    <?=\yii\bootstrap\Html::a('删除',['delete','id'=>$value->id],['class'=>'btn btn-danger btn-sm'])?>
                </td>
            </tr>
            <?php foreach (\backend\models\Menu::getChildren($value->id) as $value2): ?>
                <tr>
                    <td><?=$value2->id?></td>
                    <td><?=($value2->pid!=0?'——':'').$value2->name?></td>
                    <td><?=$value2->url?$value2->url:'\\'?></td>
                    <td>
                        <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$value2->id],['class'=>'btn btn-info btn-sm'])?>
                        <?=\yii\bootstrap\Html::a('删除',['delete','id'=>$value2->id],['class'=>'btn btn-danger btn-sm'])?>
                    </td>
                </tr>
            <?php endforeach;
        endforeach;?>
    </table>
<?php
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
    $('#w2-success-0').attr('style','display:none');
    $('#w2-danger-0').attr('style','display:none');
    $('#w5-danger-0').attr('style','display:none');
},3000);
JS
));

