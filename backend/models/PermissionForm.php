<?php
namespace backend\models;
use yii\base\Model;
//本表单模型处理权限的验证规则
class PermissionForm extends Model{
    public $name;//权限名称
    public $intro;//权限描述
    const SCENARIO_ADD='add';
    public function rules()
    {
        return [
          [['name','intro'],'required'],
            ['name','repeat','on'=>self::SCENARIO_ADD],
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'权限名称',
            'intro'=>'权限描述',
        ];
}
public function repeat(){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($this->name);
        if($permission){
            $this->addError('name','权限名称已存在');
        }
}
}