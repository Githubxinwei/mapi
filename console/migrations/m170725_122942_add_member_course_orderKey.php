<?php

use yii\db\Migration;

class m170725_122942_add_member_course_orderKey extends Migration
{
    /**
     * @数据库 - 会员课程订单表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/7/25
     */
    public function up()
    {
        $this->createIndex(
            'index_product_id',
            '{{%member_course_order}}',
            'product_id'
        );
        $this->createIndex(
            'index_id',
            '{{%member_course_order}}',
            'id'
        );
        $this->createIndex(
            'index_private_id',
            '{{%member_course_order}}',
            'private_id'
        );
        $this->createIndex(
            'index_service_pay_id',
            '{{%member_course_order}}',
            'service_pay_id'
        );
        $this->createIndex(
            'index_member_card_id',
            '{{%member_course_order}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_seller_id',
            '{{%member_course_order}}',
            'seller_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%member_course_order}}',
            'member_id'
        );
        $this->createIndex(
            'index_chargePersonId',
            '{{%member_course_order}}',
            'chargePersonId'
        );

    }

    public function down()
    {
        $this->dropIndex('index_product_id', '{{%member_course_order}}');
        $this->dropIndex('index_id', '{{%member_course_order}}');
        $this->dropIndex('index_private_id', '{{%member_course_order}}');
        $this->dropIndex('index_service_pay_id', '{{%member_course_order}}');
        $this->dropIndex('index_member_card_id', '{{%member_course_order}}');
        $this->dropIndex('index_seller_id', '{{%member_course_order}}');
        $this->dropIndex('index_member_id', '{{%member_course_order}}');
        $this->dropIndex('index_chargePersonId', '{{%member_course_order}}');
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
