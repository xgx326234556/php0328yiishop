<?php
namespace backend\models;
use yii;
use yii\web\IdentityInterface;

class GoodsIntro extends yii\db\ActiveRecord implements yii\web\IdentityInterface{


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
        // TODO: Implement getAuthKey() method.
    }


    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
    public function rules()
    {
        return [
          [['content'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [

            'content'=>'商品描述',
        ];
    }
}