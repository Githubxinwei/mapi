<?php

use yii\db\Migration;

class m171028_011439_add_bring_to_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','bring',"smallInt COMMENT '带人，0:不带人'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','bring');
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
