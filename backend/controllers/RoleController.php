<?php
namespace backend\controllers;
use backend\filters\RbacFilters;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RoleController extends Controller{
 public function actionAdd(){
     $model=new RoleForm();
     $model->scenario=RoleForm::SCENARIO_ADD;
     $authManager=\Yii::$app->authManager;
     //加载并验证数据
     if($model->load(\Yii::$app->request->post()) && $model->validate()){
         //创建一个角色
         $role=$authManager->createRole($model->name);
         //为角色添加描述信息
         $role->description=$model->intro;
         //保存角色
         $authManager->add($role);
         //为角色添加权限
         //判断改角色是否有权限$model->intro的值是一个数组
         if(is_array($model->permission)){

             foreach ($model->permission as $permissionName){
                 //得到权限数据值
              $permissions=$authManager->getPermission($permissionName);
              if($permissions){
                  //为角色赋予权限
                  $authManager->addChild($role,$permissions);
              }
             }
         }
         \Yii::$app->session->setFlash('success','添加角色成功');
         return $this->redirect(['role/index']);

     }
    return $this->render('add',['model'=>$model]);
 }
 public function actionIndex(){
     $authManager=\Yii::$app->authManager;

     $models=$authManager->getRoles();
     return $this->render('index',['models'=>$models]);
 }
 public function actionEdit($name){
     $authManager=\Yii::$app->authManager;
     $permission=\Yii::$app->authManager->getPermissionsByRole($name);
     $role=$authManager->getRole($name);
     if($role==null){
         throw new NotFoundHttpException('角色不存在');
     }
      $model=new RoleForm();
     if($model->load(\Yii::$app->request->post()) && $model->validate()){
         //全部取消关联
           $authManager->removeChildren($role);
           //$roles=$authManager->createRole($name);
           //$role->name=$model->name;
           $role->description=$model->intro;
           //更新角色
           $authManager->update($name,$role);
         if(is_array($model->permission)){

             foreach ($model->permission as $permissionName){
                 //得到权限数据值
                 $permissions=$authManager->getPermission($permissionName);
                 if($permissions){
                     //为角色赋予权限
                     $authManager->addChild($role,$permissions);
                 }
             }
         }
         \Yii::$app->session->setFlash('success','修改角色成功');
         return $this->redirect(['role/index']);

     }else{

         $model->name=$role->name;
         $model->intro=$role->description;
         $model->permission=ArrayHelper::map($permission,'name','name');

         return $this->render('add',['model'=>$model]);
     }
 }
 public function actionDelete($name){
     $authManager=\Yii::$app->authManager;
     $role=$authManager->getRole($name);
     if($role==null){
         throw new NotFoundHttpException('改角色不存在');
     }
     $authManager->remove($role);
     \Yii::$app->session->setFlash('success','删除角色成功');
     return $this->redirect(['role/index']);
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