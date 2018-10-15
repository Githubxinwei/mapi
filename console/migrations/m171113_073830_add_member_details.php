<?php

use yii\db\Migration;

class m171113_073830_add_member_details extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_details}}','ic_number',"varchar(200) COMMENT '手环ic号'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}','ic_number');
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
