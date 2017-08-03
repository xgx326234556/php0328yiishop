<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170803_093933_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer(10)->comment('用户id'),
            'name'=>$this->string(50)->comment('收货人'),
            'province'=>$this->string(50)->comment('省'),
            'city'=>$this->string(50)->comment('市'),
            'area'=>$this->string(50)->comment('县'),
            'address'=>$this->string(100)->comment('详细地址'),
            'tel'=>$this->integer(20)->comment('电话'),
            'delivery_id'=>$this->integer(10)->comment('配送方式id'),
            'delivery_name'=>$this->string(50)->comment('配送方式名称'),
            'delivery_price'=>$this->decimal(10,2)->comment('配送方式价格'),
            'payment_id'=>$this->integer(50)->comment('支付方式id'),
            'payment_name'=>$this->string(50)->comment('支付方式名称'),
            'total'=>$this->decimal(10,2)->comment('订单金额'),
            'status'=>$this->integer(10)->comment('状态'),
            'trade_no'=>$this->string(50)->comment('第三方支付交易号'),
            'create_time'=>$this->integer(50)->comment('创建时间'),
        ],'engine=innodb');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
