<?php

use yii\db\Migration;

class m170803_084959_alter_goods_table extends Migration
{
    public function safeUp()
    {
      $this->execute('alter table goods engine=innodb');
    }

    public function safeDown()
    {
        echo "m170803_084959_alter_goods_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
