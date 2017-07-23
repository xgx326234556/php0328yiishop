<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170722_030245_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->comment('商品名称'),
            'sn'=>$this->string(20)->comment('货号'),
            'logo'=>$this->string(255)->comment('Logo图片'),
            'goods_category_id'=>$this->integer(20)->comment('商品分类id'),
            'brand_id'=>$this->integer(20)->comment('品牌分类'),
            'market_price'=>$this->decimal(10,2)->comment('市场价格'),
            'shop_price'=>$this->decimal(10,2)->comment('商品价格'),
            'stock'=>$this->integer(50)->comment('商品库存'),
            'is_on_sale'=>$this->integer(1)->comment('是否在售1在售0下架'),
            'status'=>$this->integer(1)->comment('状态1正常0回收站'),
            'sort'=>$this->integer(20)->comment('排序'),
            'create_time'=>$this->integer(50)->comment('添加时间'),
            'view_times'=>$this->integer(50)->comment('游览次数'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
