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
           $tel=$model->tel;
           $model->create_time=time();
           $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
           $code2 = \Yii::$app->session->get('code_'.$tel);
           $pthon=$model->duan;
           if($pthon==$code2){
               $model->save(false);
               return $this->redirect(['login/login']);

           }else{
               $model->addError('duan','短息验证码错误');

           }

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
    public function actionDuan($teel){
        $code = rand(1000,9999);
        $tel = $teel;
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);
        //验证

    }

}