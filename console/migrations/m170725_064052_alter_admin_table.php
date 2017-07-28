<?php

use yii\db\Migration;

class m170725_064052_alter_admin_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('admin','auth_key','string');
        $this->addCommentOnColumn('admin','auth_key','密钥');
        $this->addColumn('admin','status','integer');
        $this->addCommentOnColumn('admin','status','状态');
    }

    public function safeDown()
    {
        echo "m170725_064052_alter_admin_table cannot be reverted.\n";

        //return false;
        $this->dropColumn('admin','auth_key');
        $this->dropColumn('admin','status');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170725_064052_alter_admin_table cannot be reverted.\n";

        return false;
    }
    */
}
