<?php

use yii\db\Migration;

class m170922_011302_add_pid_to_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'pid', 'int COMMENT "主卡的id"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'pid');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
