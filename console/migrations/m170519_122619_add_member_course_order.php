<?php

use yii\db\Migration;

class m170519_122619_add_member_course_order extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 新增字段 - cloud_member_course_order 会员卡表
         * @author houkaixin <houkaixin@itsports.club>
         * @create 2017/5/20
         * @inheritdoc
         */
        $this->dropColumn('{{%member_course_order}}', 'service_pay_id');
        $this->addColumn('{{%member_course_order}}', 'service_pay_id', 'bigint(20)  COMMENT "收费项目id"');
        $this->dropColumn('{{%member_course_order}}', 'member_card_id');
        $this->addColumn('{{%member_course_order}}', 'member_card_id', 'bigint(20)  COMMENT "会员卡ID"');
        $this->dropColumn('{{%member_course_order}}', 'seller_id');
        $this->addColumn('{{%member_course_order}}', 'seller_id', 'bigint(20)  COMMENT "员工表销售人id"');
        $this->addColumn('{{%member_course_order}}', 'cashierOrder', 'varchar(255) COMMENT "订单收银单号"');
        $this->dropColumn('{{%member_course_order}}', 'total_number');
        $this->dropColumn('{{%member_course_order}}', 'surplus_number');
    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}', 'service_pay_id');
        $this->dropColumn('{{%member_course_order}}', 'member_card_id');
        $this->dropColumn('{{%member_course_order}}', 'seller_id');
        $this->dropColumn('{{%member_course_order}}', 'cashierOrder');
        $this->addColumn('{{%member_course_order}}', 'service_pay_id', 'bigint(20)  COMMENT "收费项目id"');
        $this->addColumn('{{%member_course_order}}', 'member_card_id', 'bigint(20)  COMMENT "会员卡ID"');
        $this->addColumn('{{%member_course_order}}', 'seller_id', 'bigint(20)  COMMENT "员工表销售人id"');
        $this->addColumn('{{%member_course_order}}', 'total_number', 'bigint(20)  COMMENT "总课程数量"');
        $this->addColumn('{{%member_course_order}}', 'surplus_number','bigint(20)  COMMENT "剩余课程数量"');
    }


}
