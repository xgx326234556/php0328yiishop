<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList(\backend\models\Article::getArticleOptions());
echo $form->field($model,
    'article_category_id')
    ->dropDownList(\yii\helpers\ArrayHelper::map($row,'id','name'));
echo $form->field($model2,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-denger']);
\yii\bootstrap\ActiveForm::end();

