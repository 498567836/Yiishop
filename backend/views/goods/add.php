<?php
use yii\web\JsExpression;
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'goods_category_id')->hiddenInput();
    echo '<div><ul id="treeDemo" class="ztree"></ul></div>';
    echo $form->field($model,'brand_id')->dropDownList($model->Brands);
    echo $form->field($model,'market_price')->textInput(['type'=>'number']);
    echo $form->field($model,'shop_price')->textInput(['type'=>'number']);
    echo $form->field($model,'stock')->textInput(['type'=>'number']);
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($model,'is_on_sale',['inline'=>1])->radioList(\backend\models\Goods::is_on_sale_options());
    echo $form->field($model,'status',['inline'=>1])->radioList(\backend\models\Goods::status_options());

    //echo $form->field($model,'logoFile')->fileInput();
    echo $form->field($model,'logo')->hiddenInput();
    echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>120]);
    echo '<br/><br/>';
    //Remove Events Auto Convert
    //外部TAG
    echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
    echo \flyok666\uploadifive\Uploadifive::widget([
        'url' => yii\helpers\Url::to(['brand/s-upload']),
        'id' => 'test',
        'csrf' => true,
        'renderTag' => false,
        'jsOptions' => [
            'formData'=>['someKey' => 'someValue'],
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
            //console.log(data.fileUrl);
            //将图片的地址赋值给logo字段
            $("#goods-logo").val(data.fileUrl);
            console.debug($("#goods-logo").val());
            //将上传成功的图片回显
            $("#img").attr('src',data.fileUrl);
        }
    }
EOF
            ),
        ]
    ]);
    echo '<br/>';
    echo $form->field($goodsintro,'content')->widget('kucha\ueditor\UEditor',[]);
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success btn-lg ']);
    \yii\bootstrap\ActiveForm::end();

$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$categories[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];
$nodes=\yii\helpers\Json::encode($categories);
$nodeId = $model->goods_category_id;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
 var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		}
	},
	callback: {
		onClick: zTreeOnClick
	}
   };
   function zTreeOnClick(event, treeId, treeNode) {
    //将当期选中的分类的id，赋值给parent_id隐藏域
    console.debug(treeNode.id);
		 $("#goods-goods_category_id").val(treeNode.id);
    };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes ={$nodes};
   $(document).ready(function(){
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
      //zTreeObj.expandAll(true);//展开全部节点
        //获取节点
        var node = zTreeObj.getNodeByParam("id", "{$nodeId}", null);
        //选中节点
        zTreeObj.selectNode(node);
        //触发选中事件
       //$(zTreeOnClick(event, treeId, treeNode));
   });
JS
));