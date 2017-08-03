<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170802_005553_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer(10)->comment('商品id'),
            'amount'=>$this->integer(10)->comment('商品数量'),
            'member_id'=>$this->integer(10)->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
