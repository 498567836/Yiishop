<?php
    $form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'username')->textInput();
    \yii\bootstrap\ActiveForm::end();

$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
  $('#w3-danger-0').attr('style','display:none');
},3000);
JS
));