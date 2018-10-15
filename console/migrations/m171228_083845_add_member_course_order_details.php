<?php

use yii\db\Migration;

class m171228_083845_add_member_course_order_details extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_course_order_details}}','product_type',"tinyint COMMENT '产品类型:1常规pt,2特色课,3游泳课'");
        $this->addColumn('{{%member_course_order_details}}','activated_time',"int COMMENT '激活期限'");
        $this->addColumn('{{%member_course_order_details}}','transfer_num',"smallint COMMENT '转让次数'");
        $this->addColumn('{{%member_course_order_details}}','transfer_price',"decimal COMMENT '转让金额'");
        $this->addColumn('{{%member_course_order_details}}','deal_id',"bigint COMMENT '合同id'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order_details}}','product_type');
        $this->dropColumn('{{%member_course_order_details}}','activated_time');
        $this->dropColumn('{{%member_course_order_details}}','transfer_num');
        $this->dropColumn('{{%member_course_order_details}}','transfer_price');
        $this->dropColumn('{{%member_course_order_details}}','deal_id');
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
