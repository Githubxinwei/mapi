<?php

use yii\db\Migration;

class m170422_100316_add_member_course_order extends Migration
{
    /**
     * @数据库 - 会员课程订单表表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/22
     */
    public function up()
    {
        $this->addColumn('{{%member_course_order}}', 'product_id', "bigint(20) COMMENT '产品id'");
        $this->addColumn('{{%member_course_order}}', 'product_type', "int(11) COMMENT '产品类型:1私课2团课'");

    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}',"product_id");
        $this->dropColumn('{{%member_course_order}}',"product_type");
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
