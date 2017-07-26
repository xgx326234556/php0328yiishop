<?php
namespace backend\controllers;
use backend\models\Admin;
use yii\web\Controller;
use backend\models\AlterPasswordForm;
use yii\web\Request;

class AlterPasswordController extends Controller{
  public function actionPwd(){
     //验证是否是一个游客
      if(\Yii::$app->user->isGuest==false){
          //创建一个表单模型
          $model=new AlterPasswordForm();
          //判断提交方式
          $request=new Request();
          if($request->isPost){
              //加载数据
              $model->load($request->post());
              //验证数据
              if($model->validate()){
                  //得到登录用户信息
                  $row = \Yii::$app->user->identity;
                  $name = $row->username;
                  //创建模型
                  $models = Admin::findOne(['username' => $name]);
                  //验证旧密码是否正确
                  if (!\Yii::$app->security->validatePassword($model->old_password, $models->password_hash)){
                      $model->addError('old_password', '旧密码错误');
                  }elseif($model->new_password==$model->old_password){
                      $model->addError('new_password', '新旧密码不能一样');
                  }elseif ($model->new_password!==$model->again_new_password) {
                      $model->addError('again_new_password', '两次密码必须一致');
                  }else{
                      $models->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                      $models->save(false);

                  }


              }
          }
          return $this->render('add',['model'=>$model]);
      }else{
           \Yii::$app->session->setFlash('success','未登录请登录');
           return $this->redirect(['admin/login']);
      }


  }
}