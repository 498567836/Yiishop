<?php
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'password')->passwordInput();
    echo $form->field($model,'repassword')->passwordInput();
    echo $form->field($model,'email')->textInput();
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();