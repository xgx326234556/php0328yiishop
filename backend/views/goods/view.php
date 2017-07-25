<h1>商品名称：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$model->name?></h1>
<?=\yii\bootstrap\Carousel::widget([
    'items' => $model->getPics()
]);?>
<div class="container">
   商品描述： <?=$model->goodsIntro->content?>
</div>
