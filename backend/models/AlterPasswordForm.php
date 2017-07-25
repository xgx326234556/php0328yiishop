<?php
namespace backend\models;
use yii\base\Model;

class AlterPasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $again_new_password;

    public function rules()
    {
        return [
            [['old_password', 'new_password', 'again_new_password'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'old_password' => '旧密码',
            'new_password' => '新密码',
            'again_new_password' => '再次输入密码',

        ];
    }


}