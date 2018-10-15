<?php

use yii\db\Migration;

class m180130_105102_add_leave_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%leave_record}}','type',"smallint COMMENT '状态1待处理,2已同意,3已拒绝'");
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}','type');
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
