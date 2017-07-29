<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_022427_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->comment('名称'),
            'url'=>$this->string(20)->comment('权限/路由'),
            'pid'=>$this->integer()->comment('上级ID'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
