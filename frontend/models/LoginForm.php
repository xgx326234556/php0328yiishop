<?php
namespace frontend\models;
use yii\base\Model;

class LoginForm extends Model{
  public $username;
  public $password;
  public $code;
  public $rememberMe;
  public function rules()
  {
      return [
        [['username','password'],'required'],
          ['code','captcha','captchaAction'=>'login/captcha'],
          ['rememberMe', 'boolean'],
      ];
  }
 public function attributeLabels()
  {
      return [
        'username'=>'用户名:',
          'password'=>'密码:',
          'code'=>'验证码',
          'rememberMe'=>'记住密码',
      ];
  }
    public function login()
    {
        $member = Member::findOne(['username' => $this->username]);
        if ($member) {
            //验证密码
            if (\Yii::$app->security->validatePassword($this->password, $member->password_hash)) {

                $models = Member::findOne(['username' => $this->username]);
                $models->last_login_time = time();//保存登录时间
                $ip = $_SERVER['REMOTE_ADDR'];
                $models->last_login_ip = $ip;//保存登录ip
                $models->save(false);
                //将值保存到session中
                \Yii::$app->user->login($member,$this->rememberMe ? 3600 * 24 * 30 : 0 );
                return true;

            }else{
                $this->addError('password','密码错误');
            }

        }else{
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}