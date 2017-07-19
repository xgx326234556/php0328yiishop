<?php
namespace backend\controllers;
use backend\models\ArticleCategory;
use yii\data\Pagination;
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
             \Yii::$app->session->setFlash('success','添加成功了哦亲');
             return $this->redirect(['article-category/index']);
         }
     }
     return $this->render('add',['model'=>$model]);
 }
 public function actionIndex(){
     //按条件查询
     $query=ArticleCategory::find()->where(['>','status','-1']);
     //查询总条数
     $total=$query->count();
     //每页显示条数
     $perPage=2;
     //分页工具类；
     $pager=new Pagination([
         'totalCount'=>$total,
         'defaultPageSize'=>$perPage
     ]);
     $row= $query->limit($pager->limit)->offset($pager->offset)->orderBy('sort desc')->all();

     return $this->render('index',['models'=>$row,'pager'=>$pager]);
 }
 public function actionEdit($id){
     $model=ArticleCategory::findOne(['id'=>$id]);
     $request=new Request();
     if($request->isPost){
         $model->load($request->post());
         if($model->validate()){
             $model->save();
             \Yii::$app->session->setFlash('success','修改成功了哦亲');
             return $this->redirect(['article-category/index']);
         }
     }
     return $this->render('add',['model'=>$model]);
 }
 public function actionDelete($id){
     $model=ArticleCategory::findOne(['id'=>$id]);
     $model->status=-1;

     $model->save();
     \Yii::$app->session->setFlash('success','删除成功了哦亲');
     return $this->redirect(['article-category/index']);
 }
}