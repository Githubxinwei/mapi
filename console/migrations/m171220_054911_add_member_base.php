<?php

use yii\db\Migration;

class m171220_054911_add_member_base extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_base}}','mobile',"varchar(255)  COMMENT '手机号'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_base}}','mobile');
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
