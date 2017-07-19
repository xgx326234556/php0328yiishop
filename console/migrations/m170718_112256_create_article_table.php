<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170718_112256_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'article_category_id'=>$this->integer(10)->comment('文章分类id'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(2)->comment('状态'),
            'create_time'=>$this->integer(50)->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
