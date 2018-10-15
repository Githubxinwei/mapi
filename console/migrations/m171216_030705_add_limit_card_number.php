<?php

use yii\db\Migration;

class m171216_030705_add_limit_card_number extends Migration
{
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}','apply_start',"bigint COMMENT '通店开始时间'");
        $this->addColumn('{{%limit_card_number}}','apply_end',"bigint COMMENT '通店结束时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}','apply_start');
        $this->dropColumn('{{%limit_card_number}}','apply_end');
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
