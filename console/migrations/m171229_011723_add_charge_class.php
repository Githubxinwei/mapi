<?php

use yii\db\Migration;

class m171229_011723_add_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}','app_original_price',"decimal COMMENT '移动端总原价'");
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}','app_original_price');
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
