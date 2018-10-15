<?php

use yii\db\Migration;

class m170725_121901_add_deal_to_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}','deal_id',"bigInt COMMENT '合同ID'");
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}','deal_id');
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
