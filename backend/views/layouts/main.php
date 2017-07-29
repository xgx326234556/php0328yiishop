<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);


   // $menuItems = [
        /*['label' => 'Home', 'url' => ['/site/index']],
        ['label' => '管理员','items'=>[
            ['label' => '添加管理','url' => ['/admin/add']],
            ['label' => '管理员列表','url' => ['/admin/index']],

        ]],
        ['label' => '品牌','items'=>[
            ['label' => '添加品牌','url' => ['/brand/add']],
            ['label' => '品牌展示','url' => ['/brand/index']],

        ]],
        ['label' => '文章','items'=>[
            ['label' => '添加文章','url' => ['/article/add']],
            ['label' => '文章展示','url' => ['/article/index']],
            ['label' => '添加文章分类','url' => ['/article-category/add']],
            ['label' => '文章分类展示','url' => ['/article-category/index']],

        ]],

        ['label' => '商品','items'=>[
            ['label' => '添加商品','url' => ['/goods/add']],
            ['label' => '商品展示','url' => ['/goods/index']],
            ['label' => '添加商品分类','url' => ['/goods-category/add']],
            ['label' => '商品展示分类','url' => ['/goods-category/index']],

        ]],

        ['label' => '修改用户密码','items'=>[
            ['label' => '修改密码','url' => ['/alter-password/pwd']],

        ]],
        ['label' => '权限','items'=>[
            ['label' => '添加权限','url' => ['/rbac/add']],
            ['label' => '展示权限','url' => ['/rbac/index']],

        ]],
        ['label' => '角色','items'=>[
            ['label' => '添加角色','url' => ['/role/add']],
            ['label' => '展示角色','url' => ['/role/index']],

        ]],*/


    //];
    $menuItems=[];
    $models=\backend\models\Menu::find()->where(['parent_id'=>0])->all();

    foreach ($models as $model){
        $items=[];
       foreach ($model->children as $child){
           if(Yii::$app->user->can($child->path)){
               $items[] = ['label' => $child->name, 'url' => [$child->path]];
           }
       }
        if(!empty($items)){
            $menuItems[] = ['label' => $model->name, 'items' => $items];
        }
    }
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/admin/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '安全退出 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
