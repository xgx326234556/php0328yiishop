
<?php
use yii\helpers\Url;
?>
<a href="<?=Url::to(['article-category/add'])?>"class="btn bg-danger">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>

        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach($models as $model) :?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\ArticleCategory::getArticleCategoryOptions(false)[$model->status]?></td>
            <td>
                <?=\yii\bootstrap\Html::
                a('修改',['article-category/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <?=\yii\bootstrap\Html::
                a('删除',['article-category/delete','id'=>$model->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
