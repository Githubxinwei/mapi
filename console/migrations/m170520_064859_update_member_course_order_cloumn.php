<?php

use yii\db\Migration;

class m170520_064859_update_member_course_order_cloumn extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_member_course_order 会员卡表
     * @author 黄鹏举 <houkaixin@itsports.club>
     * @create 2017/5/20
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%member_course_order}}', 'member_id');
        $this->addColumn('{{%member_course_order}}', 'member_id', 'bigint(20)  COMMENT "会员id"');
        $this->dropColumn('{{%member_course_order}}', 'business_remarks');
        $this->addColumn('{{%member_course_order}}', 'business_remarks', 'text  COMMENT "业务备注"');

    }
    /**
     * @数据库 - 回滚字段 - cloud_member_course_order 会员卡表
     * @author 黄鹏举 <houkaixin@itsports.club>
     * @create 2017/5/20
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%member_course_order}}', 'member_id');
        $this->addColumn('{{%member_course_order}}', 'member_id', 'bigint(20)  COMMENT "会员id"');
        $this->dropColumn('{{%member_course_order}}', 'business_remarks');
        $this->addColumn('{{%member_course_order}}', 'business_remarks', 'text  COMMENT "业务备注"');
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
