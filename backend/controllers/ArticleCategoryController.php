<?php
namespace backend\controllers;


use backend\models\ArticleCategory;
use Symfony\Component\Yaml\Tests\A;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends Controller{
 public function actionAdd(){
     $model=new ArticleCategory();
     //判断请求方式
     $request=new Request();
     if($request->isPost){
         $model->load($request->post());
         if($model->validate()){
             $model->save();
             return $this->redirect(['article-category/index']);
         }
     }
     return $this->render('add',['model'=>$model]);
 }
 public function actionIndex(){
     $models=ArticleCategory::find()->all();
     return $this->render('index',['models'=>$models]);
 }
 public function actionEdit($id){
     $model=ArticleCategory::findOne(['id'=>$id]);
     $request=new Request();
     if($request->isPost){
         $model->load($request->post());
         if($model->validate()){
             $model->save();
             return $this->redirect(['article-category/index']);
         }
     }
     return $this->render('add',['model'=>$model]);
 }
 public function actionDelete($id){
     $model=ArticleCategory::findOne(['id'=>$id]);
     $model->status=-1;
     $model->save();
     return $this->redirect(['article-category/index']);
 }
}