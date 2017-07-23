





<table class="table table-bordered table-responsive">
    <tr>
        <td>图片</td>
        <td>修改</td>
        <td>删除</td>
    </tr>
    <tr>
        <td><?=\yii\bootstrap\Html::img($model->path,['height'=>50])?></td>
        <td>
            <?=\yii\bootstrap\Html::
            a('修改',['goods/photo-edit','id'=>$model->goods_id],['class'=>'btn btn-info'])?>
        </td>
        <td>
            <?=\yii\bootstrap\Html::
            a('删除',['goods/photo-delete','id'=>$model->goods_id],['class'=>'btn btn-info'])?>
        </td>
    </tr>

</table>
