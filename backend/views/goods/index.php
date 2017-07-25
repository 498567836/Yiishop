<?=\yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-success col-md-1']);?>
<?='<div class="col-md-2"></div>'?>
<form method="get">
     <input type="text" placeholder="搜索" name="keywords" class=" form-control col-md-1" style="width: 156px;height:31px ">
     <select name="minprice" class="form-control col-md-1" style="width: 110px;height:31px ">
        <option value="" >最小价格</option>
        <option value="<100" ><100</option>
        <option value="100" >100</option>
        <?php for($i=1;$i<=25;$i++){
            $price=$i*100*2;
            echo "<option value='$price' >$price</option>";
        }?>
      </select>
      <select name="maxprice" class="form-control col-md-1" style="width: 110px;height:31px ">
        <option value="">最大价格</option>
          <?php for($i=1;$i<=20;$i++){
              $price=$i*100*5;
              echo "<option value='$price' >$price</option>";
          }?>
          <option value=">10000" >>10000</option>
      </select>
     <input type="submit" class="btn btn-info btn-sm col-md-1 " value="搜索">
 </form>
<?='<div class="col-md-2"></div>'?>
<?=\yii\bootstrap\Html::a('返回',['goods/index'],['class'=>'btn btn-info btn-sm col-md-1']);?>
<?=\yii\bootstrap\Html::a('回收站',['goods/index','status'=>'del'],['class'=>'btn btn-warning btn-sm  col-md-1']);?>

    <br/><br/>
    <table class="table   table-bordered table-condensed table-striped table-hover " >
        <tr>
            <th>序号</th>
            <th>商品名称</th>
            <th>LOGO</th>
            <th>商品货号</th>
            <th>商品品牌</th>
            <th>商品分类</th>
            <th>市场价格</th>
            <th>本店价格</th>
            <th>库存</th>
            <th>排序</th>
            <th>状态</th>
            <th>是否上架</th>
            <th>添加时间</th>
            <th>浏览量</th>
            <th>编辑</th>
        </tr>
        <?php $page=isset($_GET['page'])?$_GET['page']:1; $num=$pager->defaultPageSize*($page-1)+1; foreach ($model as $brand): ?>
            <tr>
                <td><?=$num++?></td>
                <td><?=$brand->name?></td>
                <td><?=\yii\bootstrap\Html::img($brand->logo?$brand->logo:'/upload/brand/default.jpg',['height'=>50])?></td>
                <td><?=$brand->sn?></td>
                <td><?=$brand->Brands[$brand->brand_id]?></td>
                <td><?=$brand->GoodsCategorys[$brand->goods_category_id]?></td>
                <td><?=$brand->market_price?></td>
                <td><?=$brand->shop_price?></td>
                <td><?=$brand->stock?></td>
                <td><?=$brand->sort?></td>
                <td><?=\backend\models\Goods::status_options()[$brand->status]?></td>
                <td><?=\backend\models\Goods::is_on_sale_options()[$brand->is_on_sale]?></td>
                <td><?=date('Y-m-d',$brand->create_time)?></td>
                <td><?=$brand->view_times?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('详情',['goods/show','id'=>$brand->id],['class'=>'btn btn-info btn-sm glyphicon glyphicon-list-alt'])?>
                    <?=\yii\bootstrap\Html::a('相册',['goods/photos','id'=>$brand->id],['class'=>'btn btn-success btn-sm glyphicon glyphicon-picture']).'<br/>'?>
                    <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$brand->id],['class'=>'btn btn-warning btn-sm glyphicon glyphicon-pencil',])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-sm glyphicon glyphicon-trash'])?>
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


