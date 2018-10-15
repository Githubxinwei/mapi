<?php

use yii\db\Migration;

class m170518_032006_add_entry_record_member_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%entry_record}}','member_id','bigint(20) COMMENT "会员ID"');
    }

    public function down()
    {
        $this->dropColumn('{{%entry_record}}','member_id');
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
