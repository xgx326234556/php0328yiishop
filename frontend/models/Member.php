<?php
namespace frontend\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Member extends ActiveRecord implements IdentityInterface{
    public $password;
    public $re_password;
    public $code;


    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }


    public function getId()
    {
        return $this->id;
    }


    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function rules()
    {
        return [
          [['username','password','tel','email','re_password'],'required'],
          ['email','email'],
          ['re_password','compare','compareAttribute'=>'password'],
            ['code','captcha','captchaAction'=>'member/captcha']
        ];
    }
    public function attributeLabels()
    {
        return [
          'username'=>'用户名',
            'password'=>'密码',
            'tel'=>'电话',
            'email'=>'邮箱',
            're_password'=>'确认密码',
            'code'=>'验证码',

        ];
    }

}