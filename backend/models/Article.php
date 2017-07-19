<?php
namespace backend\models;

use yii;
use yii\web\IdentityInterface;

class Article extends yii\db\ActiveRecord implements IdentityInterface
{
    public static function getArticleOptions($hidden_del = true)
    {
        $Options = [
            -1 => '删除',
            0 => '隐藏',
            1 => '正常',

        ];
        if ($hidden_del) {
            unset($Options['-1']);
        }
        return $Options;
    }

    public function rules()
    {
        return [
            [['name', 'intro', 'article_category_id', 'sort', 'status'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'status' => '状态',

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

    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'article_category_id']);
    }
    public function getArticleDetail(){
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }

}