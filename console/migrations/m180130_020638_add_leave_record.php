<?php

use yii\db\Migration;

class m180130_020638_add_leave_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%leave_record}}','terminate_time',"bigint COMMENT '销假时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}','terminate_time');
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
