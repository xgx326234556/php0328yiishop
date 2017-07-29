
<?php
use yii\helpers\Url;
?>
<form action="" method="get" style="float: right">
    <input  type="text" name="keyword" class="btn-group-sm" placeholder="请输入搜索内容"/>
    <input  type="submit" value="搜索" class="btn btn-danger"/>
</form>
<a href="<?=Url::to(['admin/add'])?>"class="btn bg-danger">添加</a>

<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>用户名</td>

        <td>邮箱</td>
        <td>添加时间</td>
        <td>修改时间</td>
        <td>登录时间</td>
        <td>登录ip</td>
        <td>操作</td>
    </tr>
    <?php foreach($models as $model) :?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>

            <td><?=$model->email?></td>
            <td><?=date('Y-m-d',$model->created_at)?></td>
            <td><?=date('Y-m-d',$model->updated_at)?></td>
            <td><?=date('Y-m-d',$model->last_login_time)?></td>
            <td><?=$model->last_login_ip?></td>
            <td>
            <?php

            if(Yii::$app->user->can('admin/edit')){?>

                <?=\yii\bootstrap\Html::
                a('修改',['admin/edit','id'=>$model->id],['class'=>'btn btn-info'])?>

            <?php }?>
            <?php
            if(Yii::$app->user->can('admin/delete')){?>

                <?=\yii\bootstrap\Html::
                a('删除',['admin/delete','id'=>$model->id],['class'=>'btn btn-info'])?>


            <?php }?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

