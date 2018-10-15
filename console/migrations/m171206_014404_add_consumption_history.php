<?php

use yii\db\Migration;

class m171206_014404_add_consumption_history extends Migration
{
    public function up()
    {
        $this->addColumn('{{%consumption_history}}','payment_name',"varchar(200) COMMENT '缴费名称'");
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}','payment_name');
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
