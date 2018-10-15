<?php

use yii\db\Migration;

class m180201_054821_add_entry_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%entry_record}}','note',"string COMMENT '备注'");
    }

    public function down()
    {
        $this->dropColumn('{{%entry_record}}','note');
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
