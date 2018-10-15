<?php

use yii\db\Migration;

class m170922_120427_add_binding_time_to_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'binding_time', 'bigint COMMENT "副卡的绑定时间"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'binding_time');
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
