<?php
namespace  backend\controllers;
use backend\models\GoodsCategory;
use yii\web\HttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\data\Pagination;
class GoodsCategoryController extends Controller{
  public  function  actionAdd(){
      $model=new GoodsCategory(['parent_id'=>0]);
      $request=new Request();
      if($request->isPost){
          $model->load($request->post());
          if($model->validate()){
              //判断是否有相同名字的分类
               //$rows=GoodsCategory::find()->where(['name'=>$model->name])->all();
              $row=$model->parent_id;
               $name=[];
               $rowss=GoodsCategory::find()->where(['=','parent_id',$row])->all();
               foreach($rowss as $v){
                   $name[]=$v->name;
               }

              if(in_array($model->name,$name)){
                  \Yii::$app->session->setFlash('success','同级中不能有相同分类');
                  return $this->redirect(['goods-category/add']);
              }else{
                  if($model->parent_id){
                      //非一级分类

                      $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                      if($category){
                          $model->prependTo($category);
                      }else{
                          throw new HttpException(404,'上级分类不存在');
                      }
                  }else{
                      //一级分类
                      $model->makeRoot();
                  }
              }

              \Yii::$app->session->setFlash('success','分类添加成功');
              return $this->redirect(['goods-category/index']);
          }

      }
      $categoryes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
      return $this->render('add',['model'=>$model,'categoryes'=> $categoryes]);

  }
  public function actionIndex($keyword=''){
      $query=GoodsCategory::find();
      $total=$query->count();
      $perPage=2;
      $pager=new Pagination([
          'totalCount'=>$total,
          'defaultPageSize'=>$perPage
      ]);
      $models=$query->limit($pager->limit)->offset($pager->offset)->all();
      return $this->render('index',['models'=>$models,'pager'=>$pager]);
  }
  public function actionEdit($id){
      $model=GoodsCategory::findOne(['id'=>$id]);

          $request=new Request();
          if($request->isPost){
              $model->load($request->post());

              if($model->validate()){
                  if($model->parent_id==$model->id){
                      \Yii::$app->session->setFlash('success','不能修改到自己下面');
                      return $this->redirect(['goods-category/index']);
                  }else{
                      if($model->parent_id){
                          //非一级分类
                          $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                          if($category){
                              $model->prependTo($category);
                          }else{
                              throw new HttpException(404,'上级分类不存在');
                          }
                      }else{
                          $model->makeRoot();
                      }

                      \Yii::$app->session->setFlash('success','修改成功');
                      return $this->redirect(['goods-category/index']);
                  }
                  //判断是否是一级分类

              }


          }
          $categoryes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
          return $this->render('add',['model'=>$model,'categoryes'=> $categoryes]);


  }
  public function actionDelete($id){

      $model=GoodsCategory::findOne(['id'=>$id]);
      $models=GoodsCategory::findOne(['parent_id'=>$id]);
      if($model->parent_id===0){
          \Yii::$app->session->setFlash('success','一级分类不能删除');
          return $this->redirect(['goods-category/index']);

      }else{
          if($models){
              \Yii::$app->session->setFlash('success','下面有子分类不能删除');
              return $this->redirect(['goods-category/index']);

          }else{

              $model->delete();
              \Yii::$app->session->setFlash('success','删除成功');
              return $this->redirect(['goods-category/index']);
          }

      }


  }
}