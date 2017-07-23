<?php
namespace backend\controllers;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsGallery;
use yii\data\Pagination;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
use yii\web\Request;
use yii;
use yii\helpers\Url;

class GoodsController extends Controller{
  public function actionAdd(){
      $model=new Goods();//商品表模型
      $models=new GoodsIntro();//商品内容表模型
      $modelry=new GoodsGallery();//商品相册表单模型
      $categoryes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
      $brand=Brand::find()->all();
      $request=new Request();
      if($request->isPost){
          /*$model3->load($request->post());
          if($model3->validate()){
              $a=0;
              $model3->day=date('Ymd');
              $model3->count=++$a;
              $model3->save();
          }*/
          $model->load($request->post());

          if($model->validate()){
              $model->create_time=date('Ymd');
              $model->status=1;
              //根据今天的时间去查询count表中是否存在
              $row=GoodsDayCount::findOne(['day'=>$model->create_time]);
              if($row){
                  $count=++$row->count;//获取存在的商品数自加1
                  $day=date('Ymd');//获取添加时间
                  $model->sn=$day.$count;//生成货号
                  $row->count=$count;//将自加1的商品数保存到数据表
                  $row->updateAttributes(['count']);

              }else{
                  $model3=new GoodsDayCount();
                  $a=1000001;
                  $model->sn=date('Ymd').$a;
                  $model3->day=date('Ymd');
                  $model3->count=$a;
                  $model3->save();

              }

              $model->save();
          }
          $modelry->goods_id=$model->id;//为相册模型的goods_id赋值
          $modelry->save();//保存
          $models->load($request->post());
          if($models->validate()){
              $models->goods_id=$model->id;
              $models->save();
              \Yii::$app->session->setFlash('success','添加相册哦亲');
              return $this->redirect(['goods/photo','id'=>$model->id]);
          }

      }
      return $this->render('add',
          ['model'=>$model,'models'=>$models,'categoryes'=>$categoryes,'brand'=>$brand]);
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
                   $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    //实列话七牛云的类需要传配置文件
                   $action->getFilename();
                    $action->getWebUrl();
                    $action->getSavePath();

                    /*$qiniu = new Qiniu(\Yii::$app->params['qiniu']);

                    //调用上传方法两个参数文件上传的路径，文件上传的名称
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    //拿到七牛云的地址
                    $url = $qiniu->getLink($action->getWebUrl());
                    //输出拿到的七牛云地址
                    $action->output['fileUrl']=$url;*/
                },
            ],
        ];
    }
    public function actionIndex($keyword='',$sn='',$min_price='',$max_price=''){
      $query=Goods::find()->andwhere(['=','status','1']);

      if($keyword){
          $query->andWhere(['like','name',$keyword]);
      }
      if($sn){
          $query->andWhere(['like','sn',$sn]);
      }
      if($min_price)
      {
          $query->andWhere(['>=','shop_price',$min_price]);
      }
      if($max_price){
          $query->andWhere(['<=','shop_price',$max_price]);
      }

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

      return $this->render('index',['models'=>$row,'pager'=>$pager]);
    }
    public function actionDetails($id){
        $model=GoodsIntro::findOne(['goods_id'=>$id]);
        $models=Goods::findOne(['id'=>$id]);
        return $this->render('details',['model'=>$model,'models'=>$models]);
    }
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        //$models=new GoodsIntro();
        $models=GoodsIntro::findOne(['goods_id'=>$id]);
        $categoryes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $brand=Brand::find()->all();
        $request=new Request();
        if($request->isPost){
            /*$model3->load($request->post());
            if($model3->validate()){
                $a=0;
                $model3->day=date('Ymd');
                $model3->count=++$a;
                $model3->save();
            }*/
            $model->load($request->post());

            if($model->validate()){
                //$model->create_time=date('Ymd');
                //$model->status=1;
                //根据今天的时间去查询count表中是否存在
                //$row=GoodsDayCount::findOne(['day'=>$model->create_time]);
                /*/if($row){
                    $count=++$row->count;//获取存在的商品数自加1
                    $day=date('Ymd');//获取添加时间
                    $model->sn=$day.$count;//生成货号
                    $row->count=$count;//将自加1的商品数保存到数据表
                    $row->updateAttributes(['count']);

                }else{
                    $model3=new GoodsDayCount();
                    $a=1000001;
                    $model->sn=date('Ymd').$a;
                    $model3->day=date('Ymd');
                    $model3->count=$a;
                    $model3->save();

                }*/

                $model->save();
            }
            $models->load($request->post());
            if($models->validate()){
                $models->goods_id=$model->id;
                $models->save();
                \Yii::$app->session->setFlash('success','修改成功了哦亲');
                return $this->redirect(['goods/index']);
            }

        }
        return $this->render('add',
            ['model'=>$model,'models'=>$models,
                'categoryes'=>$categoryes,
                'brand'=>$brand]);
    }
    public function actionDelete($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功了哦亲');
        return $this->redirect(['goods/index']);

    }
    public function actionRecovery($keyword=''){
        $query=Goods::find()->where(['and','status=0',"name like '%{$keyword}%'"]);
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

        return $this->render('recovery',['models'=>$row,'pager'=>$pager]);
    }
    public function actionEdits($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=1;
        $model->save();
        \Yii::$app->session->setFlash('success','还原成功了哦亲');
        return $this->redirect(['goods/index']);
    }
    public function actionDeletes($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','彻底删除成功了哦亲');
        return $this->redirect(['goods/recovery']);
    }
    public function actionPhoto($id){
        $model=new GoodsGallery();
        $models=GoodsGallery::findOne(['goods_id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
              $models->path=$model->path;
              $models->save();
                \Yii::$app->session->setFlash('success','添加成功了哦亲');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('photo',['model'=>$model]);

    }
    public function actionPhotos($id){
        $model=GoodsGallery::findOne(['goods_id'=>$id]);
        if(!$model){
           echo '没有相册图片为空';?>

            <a href="<?=Url::to(['goods/index'])?>"class="btn bg-danger">回到展示页面</a>
           <?php

        }else{

            return $this->render('photos',['model'=>$model]);
        }
           return false;
    }
    public function actionPhotoEdit($id){

        $model=GoodsGallery::findOne(['goods_id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功了哦亲');

                return $this->redirect(['goods/photos','id'=>$id]);
            }
        }
       //保存
        return $this->render('photo',['model'=>$model]);

    }
    public function actionPhotoDelete($id){
        $model=GoodsGallery::findOne(['goods_id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功了哦亲');
        return $this->redirect(['goods/index']);


    }
}