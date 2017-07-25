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


use yii\web\NotFoundHttpException;

class GoodsController extends Controller{
  public function actionAdd(){
      $model=new Goods();//商品表模型
      $models=new GoodsIntro();//商品内容表模型
      $modelry=new GoodsGallery();//商品相册表单模型
      $categoryes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
      $brand=Brand::find()->all();//获取品牌分类模型
      $request=new Request();
      if($request->isPost){

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
                  $a=1000000001;
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
              \Yii::$app->session->setFlash('success','添加相册了哦亲');
              return $this->redirect(['goods/gallery','id'=>$model->id]);
          }

      }
      return $this->render('add',
          ['model'=>$model,'models'=>$models,'categoryes'=>$categoryes,'brand'=>$brand]);
  }
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }

        return $this->render('gallery',['goods'=>$goods]);

    }
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

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

                'overwriteIfExist' => true,//如果文件已存在，是否覆盖

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
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }

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

            $model->load($request->post());

            if($model->validate()){


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



    public function actionView($id)
    {
        $model = Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('view',['model'=>$model]);

    }

}