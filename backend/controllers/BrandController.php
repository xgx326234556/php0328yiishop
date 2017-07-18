<?php
namespace backend\controllers;
use backend\models\Brand;
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
        $models=Brand::find()->all();
        return $this->render('index',['models'=>$models]);
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
        $model->delete();
        return $this->redirect(['brand/index']);
    }
}