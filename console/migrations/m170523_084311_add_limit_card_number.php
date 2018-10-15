<?php

use yii\db\Migration;

class m170523_084311_add_limit_card_number extends Migration
{
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}', 'surplus',"int COMMENT '剩余张数'");
    }

    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}', 'surplus');
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
