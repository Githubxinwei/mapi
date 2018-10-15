<?php

use yii\db\Migration;

class m170811_064538_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','usage_mode',"smallint COMMENT '使用方式：1自用;2送人'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','usage_mode');
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
