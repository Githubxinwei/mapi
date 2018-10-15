<?php

use yii\db\Migration;

class m171121_021710_add_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}','many_pay_mode',"json COMMENT '多付款方式1.现金2.微信3.支付宝4.建设分期5.广发分期6.招行分期7.借记卡8.贷记卡'");
    }

    public function down()
    {
        $this->dropColumn('{{%order}}','many_pay_mode');
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
