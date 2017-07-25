<?php
namespace backend\models;
use yii\base\Model;

class LogoForm extends Model
{
    public $username;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住密码',
        ];
    }

    public function login()
    {
        $admin = Admin::findOne(['username' => $this->username]);
        if ($admin) {
            //验证密码
            if (\Yii::$app->security->validatePassword($this->password, $admin->password_hash)) {

                $models = Admin::findOne(['username' => $this->username]);
                $models->last_login_time = time();//保存登录时间
                $ip = $_SERVER['REMOTE_ADDR'];
                $models->last_login_ip = $ip;//保存登录ip
                $models->save();
                //将值保存到session中
                \Yii::$app->user->login($admin,$this->rememberMe ? 3600 * 24 * 30 : 0 );
                return true;

            }else{
                $this->addError('password','密码错误');
            }

        }else{
            $this->addError('username','用户名存在');
        }
        return false;
    }

}