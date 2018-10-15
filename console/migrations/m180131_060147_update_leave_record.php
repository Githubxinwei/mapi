<?php

use yii\db\Migration;

class m180131_060147_update_leave_record extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%leave_record}}','leave_property',"smallInt COMMENT '请假类型1.特殊请假2.正常请假3.学生请假'");
    }

    public function down()
    {
        $this->alterColumn('{{%leave_record}}','leave_property',"smallInt COMMENT '请假类型1.特殊请假2.正常请假3.学生请假'");
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
