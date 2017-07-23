<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170722_030330_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
           'goods_id'=>$this->integer(50)->comment('商品id'),
            'content'=>$this->text()->comment('商品描述'),

        ]);
        $this->addPrimaryKey('goodskey','goods_intro','goods_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
