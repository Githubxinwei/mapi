<?php

use yii\db\Migration;

class m170620_082052_add_member_course_order extends Migration
{
    /**
     * @数据库 - 会员课程订单表 - 增加字段
     * @author houkaixin <huangpengju@itsports.club>
     * @create 2017/6/21
     */
    public function up()
    {
        $this->addColumn('{{%member_course_order}}','activeTime',"bigint COMMENT '生效时间'");
        $this->addColumn('{{%member_course_order}}','chargePersonId',"bigint COMMENT '收费人员id'");

    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}','activeTime');
        $this->dropColumn('{{%member_course_order}}','chargePersonId');
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
