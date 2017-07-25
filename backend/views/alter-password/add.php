<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'old_password');
echo $form->field($model,'new_password');
echo $form->field($model,'again_new_password');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();