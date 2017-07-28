<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(
    ['readonly'=>$model->scenario!=\backend\models\RoleForm::SCENARIO_ADD]
);
echo $form->field($model,'intro');
echo $form->field($model,'permission',['inline'=>'true'])->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();