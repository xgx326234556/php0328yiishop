<?php
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
    echo $form->field($model,'intro')->textarea();
    echo $form->field($model,'sort');
    echo $form->field($model,'status')
        ->radioList(\backend\models\Brand::getStatusOptions());
    //echo $form->field($model,'logoFile')->fileInput();
    echo $form->field($model,'logo')->hiddenInput();
    echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
    echo \flyok666\uploadifive\Uploadifive::widget([
        //图片上传地址，将图片上传到那个方法里面去
    'url' => yii\helpers\Url::to(['brand/s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 50,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
     function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        //图片上传成功执行一个匿名函数的到一个字符串
        //data通过json解析字符串
        //如果有错误输出
        //如果没有错误打印路径
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    //console.log(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将图片的地址赋值给logo字段
        $("#brand-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//添加图片回显与修改图片回显
echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>50]).'<br/>';




    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-denger']);
    \yii\bootstrap\ActiveForm::end();