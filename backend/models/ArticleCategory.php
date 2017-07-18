<?php
namespace backend\models;

use yii;

use yii\web\IdentityInterface;

class ArticleCategory extends yii\db\ActiveRecord implements IdentityInterface{
    public static function getArticleCategoryOptions($hidden_del=true){
        $Options= [
            -1=>'删除',
            0=>'隐藏',
            1=>'正常',

        ];
        if($hidden_del){
            unset($Options['-1']);
        }
        return $Options;
    }
    public  function rules()
    {
        return [
          [['name','intro','sort','status'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOnine(['id'=>$id]);
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
}