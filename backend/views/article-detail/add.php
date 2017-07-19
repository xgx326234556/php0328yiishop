<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'article_id')
    ->dropDownList(\yii\helpers\ArrayHelper::map($row,'id','name'));
echo $form->field($model,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-denger']);
\yii\bootstrap\ActiveForm::end();