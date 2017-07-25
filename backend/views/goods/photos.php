<?php
use yii\web\JsExpression;
use yii\bootstrap\Html;

    //Remove Events Auto Convert
    //外部TAG
    echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
    echo \flyok666\uploadifive\Uploadifive::widget([
        'url' => yii\helpers\Url::to(['goods/s-upload']),
        'id' => 'test',
        'csrf' => true,
        'renderTag' => false,
        'jsOptions' => [
            'formData'=>['goods_id' =>$goods_id],
            'width' => 220,
            'height' => 40,
            'background'=>'red',
            'onError' => new JsExpression(<<<EOF
    function(file, errorCode, errorMsg, errorString) {
        console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
    }
EOF
            ),
            'onUploadComplete' => new JsExpression(<<<EOF
    function(file, data, response) {
        data = JSON.parse(data);
        //console.log(data);
        if (data.error) {
            console.log(data.msg);
        } else {
            console.log(data.fileUrl);
            //将图片的地址赋值给path字段
            //$("#brand-path").val(data.fileUrl);
            //将上传成功的图片回显
            //$("#img").attr('src',data.fileUrl);
            var html='<tr id="'+data.id+'" >';
            html += '<td><img src="'+data.fileUrl+'" height="200px" /></td>';
            html += '<td><button type="button" class="btn btn-danger del_btn">删除</button></td>';
            html += '</tr>';
            $("table").append(html);
        }
    }

EOF
            ),
        ]
    ]);
    //echo \yii\bootstrap\Html::img($photo->path?$photo->path:false,['id'=>'img','height'=>120]);
    ?>
<br/><br/>
<table  class="table table-bordered">
    <tr>
        <th class="col-md-8">图片</th>
        <th class="col-md-2">操作</th>
    </tr>
    <?php foreach ($photos as $photo):?>
        <tr id="<?=$photo->id?>">
            <td><?=Html::img($photo->path,['height'=>200])?></td>
            <td><?=Html::button('删除',['class'=>'btn btn-danger del_btn'])?></td>
        </tr>
    <?php endforeach;?>
</table>

<?php
$url=\yii\helpers\Url::to(['goods/delphoto']);
$this->registerJs(new \yii\web\JsExpression(
<<<JS
$('table').on('click','.del_btn',function() {
  var id=$(this).closest('tr').attr('id');
  console.debug(id);
  $.post("{$url}",{id:id},function(data) {
     if(data=="success"){
                    $("#"+id).remove();
                    //console.debug(data);
                }
  })
  
})

JS
));