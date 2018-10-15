<?php

use yii\db\Migration;

class m180209_094230_add_employee extends Migration
{
    public function up()
    {
        $this->addColumn('{{%employee}}','work_date',"date COMMENT '从业日期'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}','work_date');
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
