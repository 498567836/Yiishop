<?=\yii\bootstrap\Html::a('添加商品',['article_category/add'],['class'=>'btn btn-success']);?>
    <br/><br/>
    <table class="table   table-bordered table-condensed table-striped table-hover " >
        <tr>
            <th>分类编号</th>
            <th>分类名称</th>
            <th>分类介绍</th>
            <th>分类排序</th>
            <th>分类状态</th>
            <th>编辑</th>
        </tr>
        <?php foreach ($model as $brand): ?>
            <tr>
                <td><?=$brand->id?></td>
                <td><?=$brand->name?></td>
                <td><?=$brand->intro?></td>
                <td><?=$brand->sort?></td>
                <td><?=\backend\models\ArticleCategory::status_options(true)[$brand->status]?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['article_category/edit','id'=>$brand->id],['class'=>'btn btn-info btn-sm'])?>
                    <?=\yii\bootstrap\Html::a('删除',['article_category/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-sm'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);



