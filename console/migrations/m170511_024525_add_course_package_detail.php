<?php

use yii\db\Migration;

class m170511_024525_add_course_package_detail extends Migration
{
    public function up()
    {
        $this->addColumn('{{%course_package_detail}}', 'category', 'smallint COMMENT "类型:1多课程，2单课程"');
    }

    public function down()
    {
        $this->dropColumn('{{%course_package_detail}}', 'category');
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
