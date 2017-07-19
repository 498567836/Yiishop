<?=\yii\bootstrap\Html::a('添加商品',['brand/add'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-9"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['brand/index'],['class'=>'btn btn-info btn-sm  col-md-1']);?>
<?=\yii\bootstrap\Html::a('回收站',['brand/index','status'=>'del'],['class'=>'btn btn-warning btn-sm  col-md-1']);?>
<br/><br/>
<table class="table   table-bordered table-condensed table-striped table-hover " >
    <tr>
        <th>商品编号</th>
        <th>商品名称</th>
        <th>商品介绍</th>
        <th>商品LOGO</th>
        <th>商品排序</th>
        <th>商品状态</th>
        <th>编辑</th>
    </tr>
    <?php foreach ($model as $brand): ?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=\yii\bootstrap\Html::img($brand->logo?$brand->logo: ( '/upload/brand/default.jpg'),['height'=>60])?></td>
            <td><?=$brand->sort?></td>
            <td><?=\backend\models\Brand::status_options(true)[$brand->status]?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);


