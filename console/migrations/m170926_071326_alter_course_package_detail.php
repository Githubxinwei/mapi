<?php

use yii\db\Migration;

class m170926_071326_alter_course_package_detail extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%course_package_detail}}',"original_price","decimal(10,2) COMMENT '单节原价'");
    }

    public function down()
    {
        $this->alterColumn('{{%course_package_detail}}',"original_price","decimal(10,2) COMMENT '单节原价'");
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
