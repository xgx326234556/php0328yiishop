<?php
namespace backend\models;
use yii;
use yii\web\IdentityInterface;

class Admin extends yii\db\ActiveRecord implements IdentityInterface{


    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {

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
            [['username','password_hash',
                'email'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'username'=>'用户名',
            'password_hash'=>'密码',
            'email'=>'邮箱',

        ];
    }
}