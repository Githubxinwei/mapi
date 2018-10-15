<?php

use yii\db\Migration;

class m180110_032617_add_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}','other_note',"text COMMENT '其它备注（该字段不用于对外展示)'");
    }

    public function down()
    {
        $this->dropColumn('{{%order}}','other_note');
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
