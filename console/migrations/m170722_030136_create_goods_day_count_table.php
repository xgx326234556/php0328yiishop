<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_count`.
 */
class m170722_030136_create_goods_day_count_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'day'=>$this->date()->comment('日期'),
            'count'=>$this->integer(50)->comment('商品数'),
        ]);
        $this->addPrimaryKey('daykey','goods_day_count','day');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
    }
}
