<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');

echo $form->field($model,'password');
echo $form->field($model,'email');
echo $form->field($model,'role',['inline'=>'true'])->checkboxList(\yii\helpers\ArrayHelper::map(
    Yii::$app->authManager->getRoles(),'name','description'
));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();