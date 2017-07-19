<?php
use yii\helpers\Url;
?>
    <form action="" method="get">
        <input  type="text" name="keyword" class="btn-group-sm"/>
        <input  type="submit" value="搜索" class="btn btn-danger"/>
    </form>
    <a href="<?=Url::to(['article/add'])?>"class="btn bg-danger">添加</a>

    <table class="table table-bordered table-responsive">
        <tr>
            <td>ID</td>
            <td>名称</td>
            <td>简介</td>
            <td>文章分类</td>
            <td>排序</td>
            <td>状态</td>
            <td>添加时间</td>
            <td>内容</td>
            <td>查看</td>
            <td>操作</td>
        </tr>
        <?php foreach($models as $model) :?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=$model->intro?></td>
                <td><?=$model->articleCategory->name?></td>
                <td><?=$model->sort?></td>
                <td><?=\backend\models\Article::getArticleOptions(false)[$model->status]?></td>
                <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
                <td><?=mb_substr($model->articleDetail->content,0,8).'.....'?></td>
                <td><?=\yii\bootstrap\Html::a('查看',['article/kan','id'=>$model->id],['class'=>'btn btn-info'])?></td>
                <td>
                    <?=\yii\bootstrap\Html::
                    a('修改',['article/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <?=\yii\bootstrap\Html::
                    a('删除',['article/delete','id'=>$model->id],['class'=>'btn btn-info'])?>

                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,
    'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

?>