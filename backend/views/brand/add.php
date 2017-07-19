<?php
use yii\web\JsExpression;
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($model,'status',['inline'=>1])->radioList($status);
    echo $form->field($model,'intro')->textInput();
    //echo $form->field($model,'logoFile')->fileInput();
    echo $form->field($model,'logo')->hiddenInput();
    echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>50]);
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
        'width' => 120,
        'height' => 40,
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
        //将图片的地址赋值给logo字段
        $("#brand-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);

    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();