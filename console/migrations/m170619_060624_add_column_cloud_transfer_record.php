<?php

use yii\db\Migration;

class m170619_060624_add_column_cloud_transfer_record extends Migration
{
    /**
     * @数据库 - 添加字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/19
     */
    public function up()
    {
        $this->addColumn('{{%transfer_record}}','register_person',"varchar(200) COMMENT '登记人'");
        $this->addColumn('{{%transfer_record}}','note',"varchar(200) COMMENT '转让原因'");
        $this->addColumn('{{%transfer_record}}','cashier_number',"varchar(200) COMMENT '收银单号'");
        $this->addColumn('{{%transfer_record}}','cashier',"varchar(200) COMMENT '收银员'");
        $this->addColumn('{{%transfer_record}}', 'cash_payment',"decimal(10,2) COMMENT '现金支付'");
        $this->addColumn('{{%transfer_record}}', 'bank_card_payment',"decimal(10,2) COMMENT '银行卡支付'");
        $this->addColumn('{{%transfer_record}}', 'network_payment',"decimal(10,2) COMMENT '网络支付'");
        $this->addColumn('{{%transfer_record}}', 'member_card',"decimal(10,2) COMMENT '会员卡（余额）支付'");
        $this->addColumn('{{%transfer_record}}', 'discount',"decimal(10,2) COMMENT '折扣折让'");
        $this->addColumn('{{%transfer_record}}', 'coupon',"decimal(10,2) COMMENT '优惠卷'");
        $this->addColumn('{{%transfer_record}}', 'transfer',"decimal(10,2) COMMENT '转账'");
        $this->addColumn('{{%transfer_record}}', 'other',"decimal(10,2) COMMENT '其他'");
        $this->addColumn('{{%transfer_record}}', 'integral',"decimal(10,2) COMMENT '积分'");
        $this->addColumn('{{%transfer_record}}', 'spare',"decimal(10,2) COMMENT '备用'");
        $this->addColumn('{{%transfer_record}}', 'spare_one',"decimal(10,2) COMMENT '备用1'");
        $this->addColumn('{{%transfer_record}}', 'spare_two',"decimal(10,2) COMMENT '备用2'");
    }
    /**
     * @数据库 - 回滚字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/19
     */
    public function down()
    {
        $this->dropColumn('{{%transfer_record}}', 'register_person');
        $this->dropColumn('{{%transfer_record}}', 'note');
        $this->dropColumn('{{%transfer_record}}', 'cashier_number');
        $this->dropColumn('{{%transfer_record}}', 'cashier');
        $this->dropColumn('{{%transfer_record}}', 'cash_payment');
        $this->dropColumn('{{%transfer_record}}', 'bank_card_payment');
        $this->dropColumn('{{%transfer_record}}', 'network_payment');
        $this->dropColumn('{{%transfer_record}}', 'member_card');
        $this->dropColumn('{{%transfer_record}}', 'discount');
        $this->dropColumn('{{%transfer_record}}', 'coupon');
        $this->dropColumn('{{%transfer_record}}', 'transfer');
        $this->dropColumn('{{%transfer_record}}', 'other');
        $this->dropColumn('{{%transfer_record}}', 'integral');
        $this->dropColumn('{{%transfer_record}}', 'spare');
        $this->dropColumn('{{%transfer_record}}', 'spare_one');
        $this->dropColumn('{{%transfer_record}}', 'spare_two');
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
