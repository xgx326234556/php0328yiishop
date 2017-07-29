<?php
use yii\helpers\Url;
?>
    <a href="<?=Url::to(['article-detail/add'])?>"class="btn bg-danger">添加</a>
    <table class="table table-bordered table-responsive">
        <tr>

            <td>所属文章</td>
            <td>内容</td>
            <td>操作</td>
        </tr>
        <?php foreach($models as $model) :?>
            <tr>
                <td><?=$model->article->name?></td>
                <td><?=$model->content?></td>
                <td>
                    <?php
                    if(Yii::$app->user->can('article-detail/edit')){?>

                        <?=\yii\bootstrap\Html::
                        a('修改',['article-detail/edit','id'=>$model->id],['class'=>'btn btn-info'])?>

                    <?php }?>
                    <?php
                    if(Yii::$app->user->can('article-detail/delete')){?>

                        <?=\yii\bootstrap\Html::
                        a('删除',['article-detail/delete','id'=>$model->id],['class'=>'btn btn-info'])?>


                    <?php }?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,
    'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

?>