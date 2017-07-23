<?php
namespace backend\models;
use yii;
use yii\web\IdentityInterface;

class GoodsGallery extends yii\db\ActiveRecord implements yii\web\IdentityInterface{


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

    }


    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
    public function rules()
    {
        return [
          [['path'],'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'path'=>'上传相册图片' ,
        ];
    }
}