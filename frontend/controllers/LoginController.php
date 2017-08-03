<?php
namespace frontend\controllers;
use Codeception\Module\Yii1;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\Request;

class LoginController extends Controller{
    //public $layout=false;
  public function actionLogin()
  {
      $model = new LoginForm();
      $rquest = new Request();
      if ($rquest->isPost) {
          $model->load($rquest->post());
          if ($model->validate() && $model->login()) {
              //输出登录成功
              $member_id = \Yii::$app->user->identity->getId();
              $cookies = \Yii::$app->request->cookies;
              $carts = unserialize($cookies->get('goods'));
              if ($carts) {

                  foreach (array_keys($carts) as $v) {
                      $model = new Cart();
                      $models = Cart::find()
                          ->andWhere(['member_id' => $member_id])
                          ->andWhere(['goods_id' => $v])
                          ->one();
                      if (!$models) {
                          $model->amount = $carts[$v];
                          $model->goods_id = $v;
                          $model->member_id = $member_id;
                          $model->save(false);
                      } else {
                          $models->amount += $carts[$v];
                          $models->save();
                      }

                  }
                  \Yii::$app->response->cookies->remove('goods');
              }


              \yii::$app->session->setFlash('success', '登录成功');
              return $this->redirect(['index/index']);
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
    public function actionUser()
    {
        //可以通过 Yii::$app->user 获得一个 User实例，
        $user = \Yii::$app->user;

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        //var_dump($identity);

        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
        //var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($isGuest);
    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login/login']);
    }

}