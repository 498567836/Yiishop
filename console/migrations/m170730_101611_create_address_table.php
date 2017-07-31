<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170730_101611_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->comment('用户ID'),
            'username'=>$this->string(20)->comment('收货人'),
            'tel'=>$this->string(11)->comment('电话号码'),
            'province'=>$this->string(10)->comment('省份'),
            'city'=>$this->string(10)->comment('城市'),
            'zone'=>$this->string(10)->comment('区县'),
            'address'=>$this->string(100)->comment('详细地址'),
            'status'=>$this->integer()->defaultValue(0)->comment('状态（1为默认地址）'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
