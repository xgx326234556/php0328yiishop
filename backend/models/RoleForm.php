<?php
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model{
    public $name;//角色名称
    public $intro;//角色描述
    public $permission;//角色权限
    const SCENARIO_ADD='add';
    public function rules()
    {
        return [
          [['name','intro'],'required'],
            ['name','repeat','on'=>self::SCENARIO_ADD],
            ['permission','safe'],
        ];

    }
    public function attributeLabels()
    {
        return [
          'name'=>'角色名称',
            'intro'=>'角色描述',
            'permission'=>'角色权限',
        ];
    }
    public function repeat(){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($this->name);

        if($role){
            $this->addError('name','角色已经存在');
        }
    }
}