<?php

use yii\db\Migration;

class m170518_083651_add_member_course_order extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_member_course_order(课程订单表)
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/5/10
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_course_order}}', 'private_type', 'varchar(255) COMMENT "私教类别"');
        $this->addColumn('{{%member_course_order}}','charge_mode', 'smallint COMMENT "计费方式（1.计次课程 2.其它）"');
        $this->addColumn('{{%member_course_order}}','class_mode', 'smallint COMMENT "上课方式（1.单个教练 2. 多个教练 3.其它）"');
        $this->addColumn('{{%member_course_order}}','is_same_class', 'smallint COMMENT "是否同时上课（1:同时上课 2.不同时上课3.其它）"');
        $this->addColumn('{{%member_course_order}}','private_id', 'bigint(20) UNSIGNED COMMENT "私教id"');
        $this->addColumn('{{%member_course_order}}','total_number', 'bigint(20) UNSIGNED COMMENT "私教总次数"');
        $this->addColumn('{{%member_course_order}}','surplus_number', 'bigint(20) UNSIGNED COMMENT "私教剩余次数"');
        $this->addColumn('{{%member_course_order}}','present_course_number', 'bigint(20) UNSIGNED COMMENT "赠课总次数"');
        $this->addColumn('{{%member_course_order}}','surplus_course_number', 'bigint(20) UNSIGNED COMMENT "剩余总课数"');
        $this->addColumn('{{%member_course_order}}','business_remarks', 'text COMMENT "业务备注"');
        $this->addColumn('{{%member_course_order}}', 'cashier_type','smallint COMMENT "收银类型（1.全款 2.转让 3.回款）"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}', 'private_type');
        $this->dropColumn('{{%member_course_order}}', 'charge_mode');
        $this->dropColumn('{{%member_course_order}}', 'class_mode');
        $this->dropColumn('{{%member_course_order}}', 'is_same_class');
        $this->dropColumn('{{%member_course_order}}', 'private_id');
        $this->dropColumn('{{%member_course_order}}', 'total_number');
        $this->dropColumn('{{%member_course_order}}', 'surplus_number');
        $this->dropColumn('{{%member_course_order}}', 'present_course_number');
        $this->dropColumn('{{%member_course_order}}', 'surplus_course_number');
        $this->dropColumn('{{%member_course_order}}', 'business_remarks');
        $this->dropColumn('{{%member_course_order}}', 'cashier_type');
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
