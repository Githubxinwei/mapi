<?php

use yii\db\Migration;

class m171128_092015_add_member_details extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_details}}','note',"varchar(200) COMMENT '备注'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}','note',"varchar(200) COMMENT '备注'");
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
