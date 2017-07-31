<?php
namespace frontend\controllers;
use frontend\models\Member;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller{
 //public $layout=false;
 //public $enableCsrfValidation=false;
  public function actionAdd(){

      $model=new Member();
      $request=new Request();
      if($request->isPost){
       $model->load($request->post());
       if($model->validate()){
           $model->create_time=time();
           $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
           $model->save(false);
       }

      }

      return $this->render('add',['model'=>$model]);
  }
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }

}