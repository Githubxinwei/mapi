<?php

use yii\db\Migration;

class m170709_031311_add_member_course_order extends Migration
{
    /**
     * @数据库 - 会员课程订单表 - 添加字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/7/9
     */
    public function up()
    {
        $this->addColumn('{{%member_course_order}}','set_number',"smallint(6) COMMENT '存“服务套餐”的总套数或者“单节课程”的总数量'");
    }
    /**
     * @数据库 - 会员课程订单表 - 回滚字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/7/9
     */
    public function down()
    {
        $this->dropColumn('{{%member_course_order}}','set_number');
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
