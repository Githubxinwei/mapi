<?php

use yii\db\Migration;

class m170516_103747_add_leave_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%leave_record}}','status', 'smallint COMMENT "类型:1.假期中 2:已销假"');
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}','status');
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
