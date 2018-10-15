<?php

use yii\db\Migration;

class m170825_073519_add_leave_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%leave_record}}', 'reject_note', 'varchar(200) COMMENT "拒绝原因"');
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}', 'reject_note');
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
