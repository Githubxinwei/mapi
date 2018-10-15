<?php

use yii\db\Migration;

class m180126_073721_add_card_category extends Migration
{
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'student_leave_limit', "JSON  COMMENT '学生暑假请假天数,学生寒假请假次数[天，次],[天，次]'");
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'student_leave_limit');
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
