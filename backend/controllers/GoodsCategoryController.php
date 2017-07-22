<?php
namespace  backend\controllers;
use backend\models\GoodsCategory;
use yii\web\HttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Exception;
class GoodsCategoryController extends Controller{
  public  function  actionAdd(){
      $model=new GoodsCategory(['parent_id'=>0]);
      $request=new Request();
      if($request->isPost){
          $model->load($request->post());
          if($model->validate()){
              //判断是否有相同名字的分类
               //$rows=GoodsCategory::find()->where(['name'=>$model->name])->all();
              //获取提交的parent_id值
              $row=$model->parent_id;
               $name=[];
               //根据提交的parent_id查询所有的数据
               $rowss=GoodsCategory::find()->where(['=','parent_id',$row])->all();
               //遍历出同一级下面的子分类并将name值放到数组中
               foreach($rowss as $v){
                   $name[]=$v->name;
               }
               //判断提交的name值是否存在数组中如果存在则同级下面不能同名
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
  public function actionIndex( $keyword=''){

      //$keyword=$_GET['keyword'];
      $query=GoodsCategory::find()->where(['like','name',$keyword]);
      $total=$query->count();
      $perPage=6;
      $pager=new Pagination([
          'totalCount'=>$total,
          'defaultPageSize'=>$perPage
      ]);
      $models=$query->limit($pager->limit)->offset($pager->offset)->orderBy('tree ASC,lft ASC')->all();
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
                      try{
                          if($model->parent_id){
                              //非一级分类
                              $category=GoodsCategory::findOne(['id'=>$model->parent_id]);
                              if($category){
                                  $model->prependTo($category);
                              }else{
                                  throw new HttpException(404,'上级分类不存在');
                              }
                          }else{
                              if($model->oldAttributes['parent_id'==0]){
                                  $model->save();
                              }else{
                                  $model->makeRoot();
                              }

                          }

                          \Yii::$app->session->setFlash('success','修改成功');
                          return $this->redirect(['goods-category/index']);
                      }catch (Exception $e){
                          $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
                      }

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
      //if($model->parent_id===0){
          //\Yii::$app->session->setFlash('success','一级分类不能删除');
          //return $this->redirect(['goods-category/index']);

      //}else{
          if($models){
              \Yii::$app->session->setFlash('success','下面有子分类不能删除');
              return $this->redirect(['goods-category/index']);

          }else{

              $model->deleteWithChildren();
              \Yii::$app->session->setFlash('success','删除成功');
              return $this->redirect(['goods-category/index']);
          }

      //}


  }
}