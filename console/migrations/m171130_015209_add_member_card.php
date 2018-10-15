<?php

use yii\db\Migration;

class m171130_015209_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','card_attribute',"tinyint(4) COMMENT '1.个人,2.公司,3.团体,4学生卡'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','card_attribute');
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
