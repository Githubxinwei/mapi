<?php

use yii\db\Migration;

class m170607_022506_add_member_details extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_details}}', 'now_address',"text COMMENT '现居地'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}', 'now_address');
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
