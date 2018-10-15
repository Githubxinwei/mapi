<?php

use yii\db\Migration;

class m171230_055322_add_member_deposit extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_deposit}}','is_use',"tinyint(1) COMMENT '是否使用1.未使用2.已使用'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_deposit}}','is_use');
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
