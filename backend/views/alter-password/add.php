<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'old_password')->passwordInput();
echo $form->field($model,'new_password')->passwordInput();
echo $form->field($model,'again_new_password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();