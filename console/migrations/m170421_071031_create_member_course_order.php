<?php

use yii\db\Migration;

class m170421_071031_create_member_course_order extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_member_course_order 会员课程订单表
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/4/21
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员课程订单表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_course_order}}', [
            'id' => $this->bigPrimaryKey()->notNull()->comment('自增ID'),
            'member_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('会员ID'),
            'member_card_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('会员卡ID'),
            'service_pay_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('收费项目ID'),
            'seller_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('员工表销售人id'),
            'course_amount' => $this->smallInteger()->unsigned()->comment('总节数'),
            'create_at' => $this->bigInteger()->unsigned()->comment('买课时间'),
            'money_amount' => $this->decimal()->comment('总金额'),
            'overage_section'            => $this->integer()->unsigned()->comment('剩余节数'),
            'deadline_time' => $this->bigInteger()->unsigned()->comment('课程截止时间'),

        ], $tableOptions);

        $this->createIndex(
            'index_member_id',
            '{{%member_course_order}}',
            'member_id'
        );
        $this->createIndex(
            'index_member_card_id',
            '{{%member_course_order}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_service_pay_id',
            '{{%member_course_order}}',
            'service_pay_id'
        );
        $this->createIndex(
            'index_seller_id',
            '{{%member_course_order}}',
            'seller_id'
        );
    }

    public function down()
    {
//        $this->dropIndex('index_member_id', '{{%member_course_order}}');
//        $this->dropIndex('index_member_card_id', '{{%member_course_order}}');
//        $this->dropIndex('index_service_pay_id', '{{%member_course_order}}');
//        $this->dropIndex('index_seller_id', '{{%member_course_order}}');
        $this->dropTable('{{%member_course_order}}');
    }
}
