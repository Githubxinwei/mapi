<?php

use yii\db\Migration;

class m171101_121045_add_member_base extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_base}}','company_id',"bigint COMMENT '公司id'");
        $this->addColumn('{{%member_base}}','venue_id',"bigint COMMENT '场馆id'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_base}}','company_id');
        $this->dropColumn('{{%member_base}}','venue_id');
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
