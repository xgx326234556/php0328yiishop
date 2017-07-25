

<?php
use yii\helpers\Url;
use backend\models\GoodsGallery;


$model=GoodsGallery::find()->where(['goods_id'=>$idd])->all();

?>


<a href="<?=Url::to(['goods/photo','id'=>$idd,'goods'=>$model])?>"class="btn bg-danger">添加</a>




<table class="table table-bordered table-responsive">
    <tr>
        <td>图片</td>

        <td>删除</td>
    </tr>

    <?php foreach($models as $model) :?>
    <tr>
        <td><?=\yii\bootstrap\Html::img($model->path,['height'=>50])?></td>
        <!--<td>
            <?/*=\yii\bootstrap\Html::
            a('修改',['goods/photo-edit','id'=>$model->goods_id],['class'=>'btn btn-info'])*/?>
        </td>-->
        <td>
            <?=\yii\bootstrap\Html::
            a('删除',['goods/photo-delete','id'=>$model->id],['class'=>'btn btn-info'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
