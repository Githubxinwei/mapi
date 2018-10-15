<?php

use yii\db\Migration;

class m170510_071816_add_consumption_history extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_consumption_history(消费记录表)
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/5/10
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%consumption_history}}', 'cashier_order', 'varchar(255) COMMENT "收银单号"');
        $this->addColumn('{{%consumption_history}}', 'cash_payment', 'decimal(10,2) COMMENT "现金付款"');
        $this->addColumn('{{%consumption_history}}', 'bank_card_payment', 'decimal(10,2) COMMENT "银行卡付款"');
        $this->addColumn('{{%consumption_history}}', 'mem_card_payment', 'decimal(10,2) COMMENT "会员卡付款"');
        $this->addColumn('{{%consumption_history}}', 'coupon_payment', 'decimal(10,2) COMMENT "优惠券"');
        $this->addColumn('{{%consumption_history}}', 'transfer_accounts', 'decimal(10,2) COMMENT "转账"');
        $this->addColumn('{{%consumption_history}}', 'other_payment', 'decimal(10,2) COMMENT "其它转账"');
        $this->addColumn('{{%consumption_history}}', 'network_payment', 'decimal(10,2) COMMENT "网络支付"');
        $this->addColumn('{{%consumption_history}}', 'integration_payment', 'decimal(10,2) COMMENT "积分支付"');
        $this->addColumn('{{%consumption_history}}', 'discount_payment', 'decimal(10,2) COMMENT "折折扣支付"');
        $this->addColumn('{{%consumption_history}}', 'venue_id', 'bigint(20) UNSIGNED COMMENT "场馆id"');
        $this->addColumn('{{%consumption_history}}', 'seller_id', 'bigint(20) UNSIGNED COMMENT "销售员id"');
        $this->addColumn('{{%consumption_history}}', 'describe', 'text COMMENT "消费描述"');
        //增加索引
        $this->createIndex('index_venue_id', '{{%consumption_history}}','venue_id');
        $this->createIndex('index_seller_id', '{{%consumption_history}}','seller_id');
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}', 'cashier_order');
        $this->dropColumn('{{%consumption_history}}', 'cash_payment');
        $this->dropColumn('{{%consumption_history}}', 'bank_card_payment');
        $this->dropColumn('{{%consumption_history}}', 'mem_card_payment');
        $this->dropColumn('{{%consumption_history}}', 'coupon_payment');
        $this->dropColumn('{{%consumption_history}}', 'transfer_accounts');
        $this->dropColumn('{{%consumption_history}}', 'other_payment');
        $this->dropColumn('{{%consumption_history}}', 'network_payment');
        $this->dropColumn('{{%consumption_history}}', 'integration_payment');
        $this->dropColumn('{{%consumption_history}}', 'discount_payment');
        $this->dropColumn('{{%consumption_history}}', 'venue_id');
        $this->dropColumn('{{%consumption_history}}', 'seller_id');
        $this->dropColumn('{{%consumption_history}}', 'describe');
    }
}
