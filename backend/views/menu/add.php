<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//$models[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenuOptions());
echo $form->field($model,'sort');
echo $form->field($model,'path');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
