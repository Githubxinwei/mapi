<?php

use yii\db\Migration;

class m171115_114158_add_limit_card_number extends Migration
{
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}','identity',"tinyint(4) COMMENT '1普通,2尊爵'");
    }

    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}','identity');
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
