<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170718_063638_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string(200)->comment('图片'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
