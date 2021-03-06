<?php
use yii\helpers\Url;
?>
    <form action="" method="get">
        <input  type="text" name="keyword" class="btn-group-sm"/>
        <input  type="submit" value="搜索" class="btn btn-danger"/>
    </form>
<a href="<?=Url::to(['brand/add'])?>"class="btn bg-danger">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>图片</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>


    </tr>
    <?php foreach($models as $model) :?>
      <tr>
          <td><?=$model->id?></td>
          <td><?=$model->name?></td>
          <td><?=$model->intro?></td>
<td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>
<td><?=$model->sort?></td>
<td><?=\backend\models\Brand::getStatusOptions(false)[$model->status]?></td>
          <td>
          <?php

          if(Yii::$app->user->can('brand/edit')){?>

                  <?=\yii\bootstrap\Html::
                  a('修改',['brand/edit','id'=>$model->id],['class'=>'btn btn-info'])?>

          <?php }?>
          <?php
          if(Yii::$app->user->can('brand/delete')){?>

                  <?=\yii\bootstrap\Html::
                  a('删除',['brand/delete','id'=>$model->id],['class'=>'btn btn-info'])?>


          <?php }?>
          </td>
</tr>
<?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,
    'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

?>