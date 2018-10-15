<?php

use yii\db\Migration;

class m170513_065056_add_member_card extends Migration
{
    /**
     * @数据库 - 会员卡表 - 添加字段
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/5/13
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'first_pay_months',"smallint(6) COMMENT '首付月数'");
        $this->addColumn('{{%member_card}}', 'first_pay_price',"decimal COMMENT '首付金额'");
        $this->addColumn('{{%member_card}}', 'month_price',"decimal COMMENT '每月还款金额'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'first_pay_months');
        $this->dropColumn('{{%member_card}}', 'first_pay_price');
        $this->dropColumn('{{%member_card}}', 'month_price');
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
