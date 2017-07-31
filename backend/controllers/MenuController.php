<?php
namespace  backend\controllers;
use backend\filters\RbacFilters;
use backend\models\Menu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class  MenuController extends Controller{
  public function actionAdd(){
      $model=new Menu();
      $request=new Request();
      if($request->isPost){
        $model->load($request->post());
        if($model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('sussecc','添加成功');
            return $this->redirect(['menu/index']);
        }
      }

      $models=Menu::find()->where(['parent_id'=>0])->all();
      return $this->render('add',['model'=>$model,'models'=>$models]);
  }
  public function actionIndex(){
      $models=Menu::find()->where(['parent_id'=>0])->all();
      return $this->render('index',['models'=>$models]);
  }
  public function actionEdit($id){
      $model=Menu::findOne(['id'=>$id]);
      $request=new Request();
      if($request->isPost){
          $model->load($request->post());
          if($model->validate()){
              if($model->parent_id && !empty($model->children)){
                  $model->addError('parent_id','只能为顶级菜单');
              }else{
                  $model->save();
                  return $this->redirect(['menu/index']);
              }
          }
      }

      $models=Menu::find()->all();
      return $this->render('add',['model'=>$model,'models'=>$models]);
  }
  public function actionDelete($id){
      $model=Menu::findOne(['id'=>$id]);
      $models=Menu::find()->where(['=','parent_id',$id])->all();
      if($models){
          throw  new NotFoundHttpException('有下级菜单不能删除');
      }
      $model->delete();
      \Yii::$app->session->setFlash('sussecc','删除成功');
      return $this->redirect(['menu/index']);
  }
  public function behaviors()
  {
      return [
        'rbac'=>[
            'class'=>RbacFilters::className(),
        ]
      ];
  }


}