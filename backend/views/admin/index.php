<?=\yii\bootstrap\Html::a('添加用户',['admin/add'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-10"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['admin/index'],['class'=>'btn btn-info btn-sm  col-md-1']);?>
<br/><br/>
<table class="table   table-bordered table-condensed table-striped table-hover " >
    <tr>
        <th>序号</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>编辑</th>
    </tr>
    <?php foreach ($model as $mode): ?>
        <tr>
            <td><?=$mode->id?></td>
            <td><?=$mode->name?></td>
            <td><?=$mode->email?></td>
            <td><?=date('Y-m-d h:i:s',$mode->last_login_time)?></td>
            <td><?=$mode->last_login_ip?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$mode->id],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['admin/delete','id'=>$mode->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);

$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
  $('#w1-success-0').attr('style','display:none');
  $('#w2-success-0').attr('style','display:none');
  $('#w3-success-0').attr('style','display:none');
  $('#w4-success-0').attr('style','display:none');
  $('#w5-success-0').attr('style','display:none');
  $('.alert-success alert fade in').attr('style','display:none');
  $('#w2-danger-0').attr('style','display:none');
},3000);
JS
));
