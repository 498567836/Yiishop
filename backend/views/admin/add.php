<?php
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput(!$model->isNewRecord?['readonly'=>"readonly"]:[]);
if($model->scenario == \backend\models\Admin::SCENARIO_EDITSELF){
    echo $form->field($model,'oldpassword')->passwordInput();
}
echo $form->field($model,'password')->passwordInput();
echo $form->field($model, 'repassword')->passwordInput();
echo $form->field($model,'email')->textInput(($model->scenario == \backend\models\Admin::SCENARIO_EDITSELF)?['readonly'=>"readonly"]:[]);
if($model->scenario != \backend\models\Admin::SCENARIO_EDITSELF) {
    echo $form->field($model,'status',['inline'=>1])->radioList($model->getstatus);
    echo $form->field($model,'roles',['inline'=>1])->checkboxList(\backend\models\Admin::getRoles());
}
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();

$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
  $('#w3-danger-0').attr('style','display:none');
},3000);
JS
));