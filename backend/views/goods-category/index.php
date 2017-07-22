<?=\yii\bootstrap\Html::a('添加分类',['goods-category/add'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-10"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['goods-category/index'],['class'=>'btn btn-info btn-sm col-md-1']);?>

    <br/><br/>
    <table class="table   table-bordered table-condensed table-striped table-hover " >
        <tr>
            <th>分类编号</th>
            <th>分类名称</th>
            <th>上级分类</th>
            <th>分类介绍</th>
            <th>编辑</th>
        </tr>
        <?php foreach ($model as $brand): ?>
            <tr>
                <td><?=$brand->id?></td>
                <td><?=$brand->name?></td>
                <td><?php if($brand->parent_id){$name=\backend\models\GoodsCategory::findOne($brand->parent_id);echo ($name->name);}else{echo'顶级分类';}?></td>
                <td><?=$brand->intro?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$brand->id],['class'=>'btn btn-info btn-sm'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-sm'])?>
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
  $('#w2-success-0').attr('style','display:none');
  $('#w2-danger-0').attr('style','display:none');
},3000);
JS
));


