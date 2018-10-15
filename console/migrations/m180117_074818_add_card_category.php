<?php

use yii\db\Migration;

class m180117_074818_add_card_category extends Migration
{
    public function up()
    {
        $this->addColumn('{{%card_category}}','app_sell_price',"decimal(10,2) COMMENT '移动端售价'");
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}','app_sell_price');
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
