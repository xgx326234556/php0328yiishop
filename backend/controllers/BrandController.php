<?php
namespace backend\controllers;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller{
    public function actionAdd(){

      $model=new Brand();
      //判断上传请求方式
        $request=new Request();
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //实列话图片上传对象
            $model->logoFile=UploadedFile::getInstance($model,'logoFile');
            //验证数据
            if($model->validate()){
                if($model->logoFile){
                    $d=\yii::getAlias('@webroot').'/upload/'.date('Ymd');

                    if(!is_dir($d)){
                        mkdir($d);

                    }
                    $path='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
                    $model->logoFile->saveAs(\yii::getAlias('@webroot').$path,false);
                    $model->logo=$path;
                }
                $model->save();
                return $this->redirect(['brand/index']);
            }

        }
      return $this->render('add',['model'=>$model]);
    }
    public function actionIndex(){
        //按条件查询
        $query=Brand::find()->where(['>','status','-1']);
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
        $row=$query->limit($pager->limit)->offset($pager->offset)->all();
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
            $model->logoFile=UploadedFile::getInstance($model,'logoFile');
            //验证数据
            if($model->validate()){
                if($model->logoFile){
                    $d=\yii::getAlias('@webroot').'/upload/'.date('Ymd');

                    if(!is_dir($d)){
                        mkdir($d);

                    }
                    $path='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->logoFile->extension;
                    $model->logoFile->saveAs(\yii::getAlias('@webroot').$path,false);
                    $model->logo=$path;
                }
                $model->save();
                return $this->redirect(['brand/index']);
            }

        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['brand/index']);
    }
}