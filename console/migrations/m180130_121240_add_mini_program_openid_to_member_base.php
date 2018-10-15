<?php

use yii\db\Migration;

class m180130_121240_add_mini_program_openid_to_member_base extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_base}}','mini_program_openid',"varchar(255) COMMENT '微信小程序openid'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_base}}','mini_program_openid');
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
