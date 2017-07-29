<?php
use yii\helpers\Url;
?>
<a href="<?=Url::to(['role/add'])?>" class="btn btn-danger">添加</a>

    <table class="table table-responsive table-bordered">
        <thead>
        <tr>
            <td>角色名称</td>
            <td>角色描述</td>
            <td>操作</td>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->name?></td>
                <td><?=$model->description?></td>
                <td>
                <?php
                if(Yii::$app->user->can('role/edit')){?>

                    <?=\yii\bootstrap\Html::
                    a('修改',['role/edit','name'=>$model->name],['class'=>'btn btn-info'])?>

                <?php }?>
                <?php
                if(Yii::$app->user->can('role/delete')){?>

                    <?=\yii\bootstrap\Html::
                    a('删除',['role/delete','name'=>$model->name],['class'=>'btn btn-info'])?>


                <?php }?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php
/**
 * @var $this \yii\web\View
 */
#$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
language: {
        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Chinese.json"
    }
});');