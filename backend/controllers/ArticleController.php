<?php
namespace  backend\controllers;
use backend\models\Article;
use backend\models\ArticleCategory;

use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller{
 public function actionAdd(){
     //查询出文章分类数据
     $row=ArticleCategory::find()->where(['>','status','-1'])->all();
     //建立文章表模型
     $model=new Article();
     //判断请求方式
     $model2=new ArticleDetail();
     $request=new Request();
     if($request->isPost){
         //加载数据
         $model->load($request->post());

         if($model->validate()){
             //添加时间
             $model->create_time=time();
             $model->save();

         }
         $model2->load($request->post());
         if($model2->validate()){
             //根据id为文章详情赋值
            $model2->article_id=$model->id;
            $model2->save();
            return $this->redirect(['article/index']);
         }
     }
     return $this->render('add',['model'=>$model,'row'=>$row,'model2'=>$model2]);
 }
 public function actionIndex(){

   $query=Article::find()->where(['>','status','-1']);
   //查询总条数
   $total=$query->count();
   //每页显示条数
     $perPage=2;
     $pager=new Pagination([
         'totalCount'=>$total,
         'defaultPageSize'=>$perPage
     ]);
     $models=$query->limit($pager->limit)->offset($pager->offset)->orderBy('sort desc')->all();
   return $this->render('index',['models'=>$models,'pager'=>$pager]);
 }
 public function actionEdit($id){
     //查询出文章表的一条数据
     $model=Article::findOne(['id'=>$id]);
     //查询出文章分类表的一条数据
     $row=ArticleCategory::find()->where(['>','status','-1'])->all();
     //查询出文章内容的一条数据
     $model2=ArticleDetail::findOne(['article_id'=>$id]);
     $request=new Request();
     if($request->isPost){
         //加载数据
         $model->load($request->post());

         if($model->validate()){
             $model->create_time=time();
             $model->save();

         }
         $model2->load($request->post());
         if($model2->validate()){
             $model2->article_id=$model->id;
             $model2->save();
             return $this->redirect(['article/index']);
         }
     }
     return $this->render('add',['model'=>$model,'row'=>$row,'model2'=>$model2]);
 }
 public function actionDelete($id){
     $model=Article::findOne(['id'=>$id]);
     $model->status=-1;
     $model->save();
     return $this->redirect(['article/index']);
 }
    public function actionKan($id){
        $model=ArticleDetail::findOne(['article_id'=>$id]);
        $models=Article::findOne(['id'=>$id]);
        return $this->render('kan',['model'=>$model,'models'=>$models]);
    }
}