<?php

use yii\db\Migration;

class m171207_105133_add_consumption_history extends Migration
{
    public function up()
    {
        $this->addColumn('{{%consumption_history}}','activate_date',"bigint(20) COMMENT '激活日期'");
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}','activate_date');
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
