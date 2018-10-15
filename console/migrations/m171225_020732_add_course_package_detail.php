<?php

use yii\db\Migration;

class m171225_020732_add_course_package_detail extends Migration
{
    public function up()
    {
        $this->addColumn('{{%course_package_detail}}','app_original',"decimal COMMENT '移动端单节原价'");
    }

    public function down()
    {
        $this->dropColumn('{{%course_package_detail}}','app_original');
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
