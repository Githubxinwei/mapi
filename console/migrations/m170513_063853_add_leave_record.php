<?php

use yii\db\Migration;

class m170513_063853_add_leave_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%leave_record}}', 'leave_start_time',"bigint(6) COMMENT '请假开始时间'");
        $this->addColumn('{{%leave_record}}', 'leave_end_time',"bigint(6) COMMENT '请假结束时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}', 'leave_start_time');
        $this->dropColumn('{{%leave_record}}', 'leave_end_time');
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
