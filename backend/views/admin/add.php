<?php
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput(!$model->isNewRecord?['readonly'=>"readonly"]:[]);
if(!$model->isNewRecord && \Yii::$app->user->id!=1){
    echo $form->field($model,'oldpassword')->passwordInput();
}
echo $form->field($model,'password')->passwordInput();
if(!$model->isNewRecord) {
    echo $form->field($model, 'repassword')->passwordInput();
}
    echo $form->field($model,'email')->textInput((!$model->isNewRecord && \Yii::$app->user->id!=1)?['readonly'=>"readonly"]:[]);
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();

$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
  $('#w3-danger-0').attr('style','display:none');
},3000);
JS
));