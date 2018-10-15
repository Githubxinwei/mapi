<?php

use yii\db\Migration;

class m171228_065619_add_member_deposit extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_deposit}}','type',"tinyint(1) COMMENT '定金类型1.购卡2.购课3.续费4.升级'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_deposit}}','type');
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
