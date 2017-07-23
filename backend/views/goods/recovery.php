
<?php
use yii\helpers\Url;
?>
<form action="<?=Url::to(['goods/recovery'])?>" method="get" style="float: right">
    <input  type="text" name="keyword" class="btn-group-sm"/>
    <input  type="submit" value="搜索" class="btn btn-danger"/>
</form>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>商品名称</td>
        <td>货号</td>
        <td>Logo图片</td>
        <td>商品分类</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>商品库存</td>
        <td>是否销售</td>
        <td>商品描述</td>
        <td>状态</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>点击数</td>
        <td>查看详情</td>
        <td>删除</td>
        <td>修改</td>
    </tr>
    <?php foreach($models as $model) :?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=$model->is_on_sale?'在售':'下架'?></td>
            <td><?=mb_substr($model->goodsIntro->content,0,8).'.....'?></td>
            <td><?=$model->status?'正常':'回收站'?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->create_time?></td>
            <td><?=$model->view_times?></td>
            <td><?=\yii\bootstrap\Html::
                a('查看',['goods/details','id'=>$model->id],['class'=>'btn btn-info'])?>
            </td>
            <td>
                <?=\yii\bootstrap\Html::
                a('还原',['goods/edits','id'=>$model->id],['class'=>'btn btn-info'])?>
            </td>
            <td>
                <?=\yii\bootstrap\Html::
                a('彻底删除',['goods/deletes','id'=>$model->id],['class'=>'btn btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,
    'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);

?>