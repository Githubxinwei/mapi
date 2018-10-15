<?php

use yii\db\Migration;

class m180102_084730_add_charge_class extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%charge_class}}','app_original_price',"decimal(10,2) COMMENT '移动端总原价'");
        $this->alterColumn('{{%charge_class}}','app_amount',"decimal(10,2) COMMENT '移动端总售价'");
        $this->alterColumn('{{%course_package_detail}}','app_original',"decimal(10,2) COMMENT '移动端单节原价'");
    }

    public function down()
    {
        $this->alterColumn('{{%charge_class}}','app_original_price',"decimal(10,2) COMMENT '移动端总原价'");
        $this->alterColumn('{{%charge_class}}','app_amount',"decimal(10,2) COMMENT '移动端总售价'");
        $this->alterColumn('{{%course_package_detail}}','app_original',"decimal(10,2) COMMENT '移动端单节原价'");
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
