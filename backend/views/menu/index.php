<?php
use yii\helpers\Url;
?>
<a href="<?=Url::to(['menu/add'])?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-responsive">
    <tr><th>名称</th>
        <th>路由</th>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->path?></td>
            <td>
                <?php
                if(Yii::$app->user->can('menu/edit')){?>

                    <?=\yii\bootstrap\Html::
                    a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-info'])?>

                <?php }?>
                <?php
                if(Yii::$app->user->can('menu/delete')){?>

                    <?=\yii\bootstrap\Html::
                    a('删除',['menu/delete','id'=>$model->id],['class'=>'btn btn-info'])?>


                <?php }?>
            </td>
        </tr>
        <?php foreach($model->children as $child):?>
            <tr>
                <td>——<?=$child->name?></td>
                <td><?=$child->path?></td>
                <td>
                    <?php
                    if(Yii::$app->user->can('menu/edit')){?>

                        <?=\yii\bootstrap\Html::
                        a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-info'])?>

                    <?php }?>
                    <?php
                    if(Yii::$app->user->can('menu/delete')){?>

                        <?=\yii\bootstrap\Html::
                        a('删除',['menu/delete','id'=>$model->id],['class'=>'btn btn-info'])?>


                    <?php }?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endforeach;?>
</table>
