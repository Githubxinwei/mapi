<?php

use yii\db\Migration;

class m170918_123153_update_member_card extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%member_card}}', 'absentTimes', "bigint DEFAULT 0 COMMENT '缺课次数'");
    }

    public function down()
    {
        $this->alterColumn('{{%member_card}}', 'absentTimes', "bigint DEFAULT 0 COMMENT '缺课次数'");
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
