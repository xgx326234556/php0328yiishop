<?php
namespace backend\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord implements IdentityInterface{

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
    public function rules()
    {
        return [
          [['name','parent_id','sort'],'required'],
            ['path','safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'名称',
            'parent_id'=>'上级菜单',
            'path'=>'路由',
            'sort'=>'排序',
        ];
    }
    public function getChildren(){
        return $this->hasMany(Menu::className(),['parent_id'=>'id']);
    }
    public static function getMenuOptions()
    {

        return ArrayHelper::merge([''=>'=请选择上级菜单=',0=>'顶级菜单'],ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','name'));
    }

}