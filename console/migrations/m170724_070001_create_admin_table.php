<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170724_070001_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->unique()->comment('用户名'),
            'password' => $this->string(100)->notNull()->comment('密码'),
            'email' => $this->string(50)->unique()->comment('邮箱'),
            'last_login_time' => $this->integer(11)->comment('最后登录时间'),
            'last_login_ip' => $this->string(50)->comment('最后登录IP'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
