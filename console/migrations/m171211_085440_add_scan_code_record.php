<?php

use yii\db\Migration;

class m171211_085440_add_scan_code_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%scan_code_record}}','member_card_id',"bigint COMMENT '会员卡id'");
    }

    public function down()
    {
        $this->dropColumn('{{%scan_code_record}}','member_card_id');
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
