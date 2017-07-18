<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
    echo $form->field($model,'intro')->textarea();
    echo $form->field($model,'sort');
    echo $form->field($model,'status')
        ->radioList(\backend\models\Brand::getStatusOptions());
    echo $form->field($model,'logoFile')->fileInput();
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-denger']);
    \yii\bootstrap\ActiveForm::end();