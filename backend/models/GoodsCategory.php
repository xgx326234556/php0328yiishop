<?php
namespace backend\models;
use yii;
use yii\web\IdentityInterface;
use creocoder\nestedsets\NestedSetsBehavior;

class GoodsCategory extends yii\db\ActiveRecord implements IdentityInterface
{
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'intro' => '简介',
            'parent_id' => '上级分类',
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

    //嵌套行为
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
    public static function getcategory($id){
        if($id==0){
            return '一级分类';
        }else{
            $model=GoodsCategory::findOne($id)->name;
            return $model;
        }
    }
}