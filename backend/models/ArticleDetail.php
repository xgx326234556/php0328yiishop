<?php
namespace  backend\models;

use yii;
use yii\web\IdentityInterface;

class ArticleDetail extends  yii\db\ActiveRecord implements IdentityInterface{
    public function rules()
    {
        return [
            ['content','required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'content'=>'文章内容',
        ];
    }

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

    }
    public function getArticle(){
        return $this->hasOne(Article::className(),['id'=>'article_id']);
    }
}