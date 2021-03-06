<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'email');
echo $form->field($model,'password')->passwordInput();
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'admin/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
echo $form->field($model,'remember')->checkbox([true]);
echo $form->field($model,'times')->dropDownList(\backend\models\LoginForm::$timesoption);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
  $('#w3-danger-0').attr('style','display:none');
},3000);
JS
));