<?php

use yii\db\Migration;

class m180103_063436_add_member_course_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_course_order}}','class_number_id',"bigint COMMENT '私课编号id'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}','class_number_id');
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
