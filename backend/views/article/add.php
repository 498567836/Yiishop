<?php
    $form=\yii\bootstrap\ActiveForm::begin();
    echo $form->field($model,'name')->textInput();
    echo $form->field($model,'article_category_id')->dropDownList($model->getArticleCategorys());
    echo $form->field($model,'intro')->textInput();
    echo $form->field($model,'sort')->textInput(['type'=>'number']);
    echo $form->field($model,'status',['inline'=>1])->radioList($status);
    //echo $form->field($articledetail,'content')->textarea(['rows'=>10,'style'=>"resize:none" ]);
    echo $form->field($articledetail,'content')->widget('kucha\ueditor\UEditor',[]);
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();