<?php

use yii\db\Migration;

class m180203_054335_add_matching_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%matching_record}}','member_id',"bigint COMMENT '会员Id'");
    }

    public function down()
    {
        $this->dropColumn('{{%matching_record}}','member_id');
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
