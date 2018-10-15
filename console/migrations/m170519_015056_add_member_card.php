<?php

use yii\db\Migration;

class m170519_015056_add_member_card extends Migration
{
    /**
     * @数据库 - 会员卡表 - 添加字段
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/5/19
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}','card_name','string(200) COMMENT "卡名"');
        $this->addColumn('{{%member_card}}','another_name','string(200) COMMENT "另一个卡名"');
        $this->addColumn('{{%member_card}}','card_type','bigint(20) COMMENT "卡类别"');
        $this->addColumn('{{%member_card}}','count_method','smallint COMMENT "计次方式:1按时效,2按次数"');
        $this->addColumn('{{%member_card}}','attributes','smallint COMMENT "属性:1个人,2家庭,3公司"');
        $this->addColumn('{{%member_card}}','active_limit_time','bigint(20) COMMENT "激活期限"');
        $this->addColumn('{{%member_card}}','transfer_num','smallint COMMENT "转让次数"');
        $this->addColumn('{{%member_card}}','transfer_price','decimal COMMENT "转让金额"');
        $this->addColumn('{{%member_card}}','recharge_price','decimal COMMENT "充值卡充值金额"');
        $this->addColumn('{{%member_card}}','renew_price','decimal COMMENT "续费价"');
        $this->addColumn('{{%member_card}}','renew_best_price','decimal COMMENT "续费优惠价"');
        $this->addColumn('{{%member_card}}','renew_unit','int COMMENT "续费多长时间/天"');
        $this->addColumn('{{%member_card}}','leave_total_days','int COMMENT "请假总天数"');
        $this->addColumn('{{%member_card}}','leave_least_days','int COMMENT "每次请假最少天数"');
        $this->addColumn('{{%member_card}}','leave_times','int COMMENT "请假次数"');
        $this->addColumn('{{%member_card}}','leave_once_days','int COMMENT "每次请假天数"');
        $this->addColumn('{{%member_card}}','deal_id','bigint(20) COMMENT "合同id"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','card_name');
        $this->dropColumn('{{%member_card}}','another_name');
        $this->dropColumn('{{%member_card}}','card_type');
        $this->dropColumn('{{%member_card}}','count_method');
        $this->dropColumn('{{%member_card}}','attributes');
        $this->dropColumn('{{%member_card}}','active_limit_time');
        $this->dropColumn('{{%member_card}}','transfer_num');
        $this->dropColumn('{{%member_card}}','transfer_price');
        $this->dropColumn('{{%member_card}}','recharge_price');
        $this->dropColumn('{{%member_card}}','renew_price');
        $this->dropColumn('{{%member_card}}','renew_best_price');
        $this->dropColumn('{{%member_card}}','renew_unit');
        $this->dropColumn('{{%member_card}}','leave_total_days');
        $this->dropColumn('{{%member_card}}','leave_least_days');
        $this->dropColumn('{{%member_card}}','leave_times');
        $this->dropColumn('{{%member_card}}','leave_once_days');
        $this->dropColumn('{{%member_card}}','deal_id');
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
