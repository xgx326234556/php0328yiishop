<?php

use yii\db\Migration;

/**
 * Handles the creation of table `path`.
 */
class m170730_063927_create_path_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('path', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->comment('收获人'),
            'shen'=>$this->string(50)->comment('所在省地区'),
            'shi'=>$this->string(50)->comment('所在市地区'),
            'xian'=>$this->string(50)->comment('所在县地区'),
            'xpath'=>$this->string(50)->comment('详细地址'),
            'tel'=>$this->integer(11)->comment('手机号码'),
            'member_id'=>$this->integer(10)->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('path');
    }
}
