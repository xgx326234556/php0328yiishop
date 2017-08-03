<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170803_094119_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer(10)->comment('订单id'),
            'goods_id'=>$this->integer(10)->comment('商品id'),
            'goods_name'=>$this->string(255)->comment('商品名称'),
            'logo'=>$this->string(255)->comment('图片'),
            'price'=>$this->decimal(10,2)->comment('价格'),
            'amount'=>$this->integer(50)->comment('数量'),
            'total'=>$this->decimal(10,2)->comment('小计'),
        ],'engine=innodb');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
