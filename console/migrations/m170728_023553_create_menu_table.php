<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_023553_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('菜单名称'),
            'parent_id'=>$this->integer(10)->comment('父类id'),
            'path'=>$this->string(50)->comment('二级菜单路由'),
            'sort'=>$this->integer(10)->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
