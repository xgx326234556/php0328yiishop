<?php
namespace backend\controllers;
use backend\filters\RbacFilters;
use backend\models\Brand;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use flyok666\qiniu\Qiniu;

class BrandController extends Controller{
    public function actionAdd(){

      $model=new Brand();
      //判断上传请求方式
        $request=new Request();
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //实列话图片上传对象
           // $model->logoFile=UploadedFile::getInstance($model,'logoFile');
            //验证数据
            if($model->validate()){
               // if($model->logoFile){
                    //$d=\yii::getAlias('@webroot').'/upload/'.date('Ymd');

                    //if(!is_dir($d)){
                       // mkdir($d);

                   // }
                    //$path='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
                    //$model->logoFile->saveAs(\yii::getAlias('@webroot').$path,false);
                   // $model->logo=$path;
                //}
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功了哦亲');
                return $this->redirect(['brand/index']);
            }

        }
      return $this->render('add',['model'=>$model]);
    }
    public function actionIndex($keyword=''){
        //按条件查询
        $query=Brand::find()->where(['and','status>-1',"name like '%{$keyword}%'"]);
        //查询出总条数
        $total=$query->count();
        //每页显示条数
        $perPage=2;
        //分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        //按条件取出数据
        $row=$query->limit($pager->limit)->offset($pager->offset)->orderBy('sort desc')->all();
        //回调视图，并将数据分配到页面
        return $this->render('index',['models'=>$row,'pager'=>$pager]);
    }
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //实列话图片上传对象
            //$model->logoFile=UploadedFile::getInstance($model,'logoFile');
            //验证数据
            if($model->validate()){
               // if($model->logoFile){
                   // $d=\yii::getAlias('@webroot').'/upload/'.date('Ymd');

                    //if(!is_dir($d)){
                       // mkdir($d);

                    //}
                    //$path='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
                   // $model->logoFile->saveAs(\yii::getAlias('@webroot').$path,false);
                   // $model->logo=$path;
               // }
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功了哦亲');
                return $this->redirect(['brand/index']);
            }

        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功了哦亲');
        return $this->redirect(['brand/index']);
    }
    public function actions() {
        //接收上传的图片
        return [
            's-upload' => [
                //文件上传实列话类
                'class' =>UploadAction::className(),
                //
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                //跨站请求
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
                     $fileext = $action->uploadfile->getExtension();
                     $filename = sha1_file($action->uploadfile->tempName);
                     return "{$filename}.{$fileext}";
                 },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                //文件的保存方式
                'format' => function (UploadAction $action) {
                    //获取图片上传字段名
                    $fileext = $action->uploadfile->getExtension();
                    //运算图片路径
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    //返回保存图片路径
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    //图片的后缀
                    'extensions' => ['jpg', 'png'],
                    //最大只能传多大图片尺寸
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //返回图片路径
                    //$action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    //实列话七牛云的类需要传配置文件
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    //调用上传方法两个参数文件上传的路径，文件上传的名称
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    //拿到七牛云的地址
                    $url = $qiniu->getLink($action->getWebUrl());
                    //输出拿到的七牛云地址
                    $action->output['fileUrl']=$url;
                },
            ],
        ];
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilters::className(),
                 'only'=>['add','edit','delete','index'],
            ]
        ];
    }

}