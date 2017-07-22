<?php
use yii\helpers\Url;
?>
    <form action="<?=Url::to(['goods-category/index'])?>" method="get" style="float: right">
        <input  type="text" name="keyword" class="btn-group-sm"/>
        <input  type="submit" value="搜索" class="btn btn-danger"/>
    </form>
    <a href="<?=Url::to(['goods-category/add'])?>"class="btn bg-danger">添加</a>
    <table class="table table-bordered table-responsive">
        <tr>
            <td>ID</td>
            <td>名称</td>
            <td>上一级分类</td>
            <td>简介</td>
            <td>操作</td>
        </tr>
        <?php foreach($models as $model) :?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=str_repeat('__',$model->depth).$model->name?></td>
                <td><?=\backend\models\GoodsCategory::getcategory($model->parent_id)?></td>
                <td><?=$model->intro?></td>
                <td>
                    <?=\yii\bootstrap\Html::
                    a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <?=\yii\bootstrap\Html::
                    a('删除',['goods-category/delete','id'=>$model->id],['class'=>'btn btn-info'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,
    'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

?>