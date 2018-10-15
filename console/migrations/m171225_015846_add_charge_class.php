<?php

use yii\db\Migration;

class m171225_015846_add_charge_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%charge_class}}','app_amount',"decimal COMMENT '移动端总售价'");
        $this->addColumn('{{%charge_class}}','show',"smallint default 1 COMMENT '移动端价格:1不显示,2显示'");
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}','app_amount');
        $this->dropColumn('{{%charge_class}}','show');
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
