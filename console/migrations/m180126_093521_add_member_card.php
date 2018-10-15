<?php

use yii\db\Migration;

class m180126_093521_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'student_leave_limit', "JSON  COMMENT '学生暑假请假天数,学生寒假请假次数[天，次],[天，次]'");
        $this->addColumn('{{%member_card}}', 'leave_type', "smallint(6)  COMMENT '请假类型:NULL正常请假,1学生请假'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','student_leave_limit');
        $this->dropColumn('{{%member_card}}','leave_type');
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
