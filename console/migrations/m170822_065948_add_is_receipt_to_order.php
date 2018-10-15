<?php

use yii\db\Migration;

class m170822_065948_add_is_receipt_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'is_receipt', "tinyint DEFAULT 0  COMMENT '0、未开票；1、已开票'");
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'is_receipt');
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
