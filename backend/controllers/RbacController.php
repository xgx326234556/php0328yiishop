<?php
namespace backend\controllers;
use backend\models\PermissionForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

//本控制器处理权限增删改查
class RbacController extends Controller{
 public function actionAdd(){
     //创建权限表单模型
     $model=new PermissionForm();
     //加入场景
     $model->scenario=PermissionForm::SCENARIO_ADD;
     //加载数据并验证数据
     if($model->load(\Yii::$app->request->post()) && $model->validate()){
      //实列化权限对象
         $authManager=\Yii::$app->authManager;
         //创建权限
         $permission=$authManager->createPermission($model->name);
         //将描述赋值
         $permission->description=$model->intro;
         //保存数据到数据表
         $authManager->add($permission);
         \Yii::$app->session->setFlash('success','添加成功');
         return $this->redirect(['rbac/index']);

     }
     return $this->render('add',['model'=>$model]);
 }
 public function actionIndex(){
     //实列化权限模型
     $authManager=\Yii::$app->authManager;
     //获取权限数据
     $models=$authManager->getPermissions();
     return $this->render('index',['models'=>$models]);
 }
 public function actionEdit($name){
     //实列化权限模型
     $authManager=\Yii::$app->authManager;
     //获取一条权限数据
     $model=new PermissionForm();
     $permission=$authManager->getPermission($name);
     if($permission==null){
         throw new NotFoundHttpException('权限不存在');
     }
     if($model->load(\Yii::$app->request->post()) && $model->validate()){
         //将表单的值赋值给权限

         $permission->name=$model->name;
         //将描述赋值
         $permission->description=$model->intro;
         //更新权限
        $authManager->update($name,$permission);
        \Yii::$app->session->setFlash('success','修改成功');
        return $this->redirect(['rbac/index']);

     }else{
         //验证通过就回显
         $model->name=$permission->name;
         $model->intro=$permission->description;
         return $this->render('add',['model'=>$model]);
     }
     //判断权限是否存在，用户可能输入无效数据

     //实列化表单模型、
 }
 public function actionDelete($name){
     $authManager=\Yii::$app->authManager;
     $permission=$authManager->getPermission($name);
     if($permission==null){
         throw new NotFoundHttpException('权限不存在');
     }
     $authManager->remove($permission);
     \Yii::$app->session->setFlash('success','删除成功');
     return $this->redirect(['rbac/index']);

 }
}