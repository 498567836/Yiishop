<?php
//var_dump(\backend\models\Menu::getMenus());exit;
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'pid')->dropDownList(\backend\models\Menu::getMenus());
    echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getPermissions());
    echo $form->field($model,'sort')->textInput(['type'=>'number']);

    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();

$this->registerJs(new \yii\web\JsExpression(
    <<<JS
window.setTimeout(function() {
    $('#w2-success-0').attr('style','display:none');
    $('#w2-danger-0').attr('style','display:none');
},3000);
JS
));