<?php

use yii\db\Migration;

class m171020_032813_add_consumption_history extends Migration
{
    public function up()
    {
        $this->addColumn('{{%consumption_history}}','due_date',"bigint COMMENT '到期日期'");
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}','due_date');
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
