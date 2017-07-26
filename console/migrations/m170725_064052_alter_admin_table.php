<?php

use yii\db\Migration;

class m170725_064052_alter_admin_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('admin','auth_key','string');
        $this->addCommentOnColumn('admin','auth_key','密钥');
    }

    public function safeDown()
    {
        echo "m170725_064052_alter_admin_table cannot be reverted.\n";

        //return false;
        $this->dropColumn('admin','auth_key');

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
