<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170729_034510_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->comment('用户名'),
            'auth_key'=>$this->string(200)->comment('密钥'),
            'password_hash'=>$this->string(200)->comment('密码'),
            'email'=>$this->string(100)->comment('邮箱'),
            'tel'=>$this->string(50)->comment('电话'),
            'last_login_time'=>$this->integer(50)->comment('最后登录时间'),
            'last_login_ip'=>$this->string(50)->comment('最后登录ip'),
            'status'=>$this->integer(10)->comment('状态'),
            'create_time'=>$this->integer(50)->comment('添加时间'),
            'update_time'=>$this->integer(50)->comment('修改时间'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
