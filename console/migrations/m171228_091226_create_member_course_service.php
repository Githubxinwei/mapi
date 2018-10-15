<?php

use yii\db\Migration;

class m171228_091226_create_member_course_service extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员私课服务赠品表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_course_service}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'course_order_id' => $this->bigInteger()->unsigned()->notNull()->comment('	课程订单表id	'),
            'service_id'      => $this->bigInteger()->unsigned()->comment('服务ID'),
            'gift_id'         => $this->bigInteger()->unsigned()->comment('赠品ID'),
            'type'            => $this->smallInteger()->unsigned()->comment('类型：1服务，2赠品'),
            'category'        => $this->smallInteger()->unsigned()->comment('类别：1私课，2团课'),
            'create_time'     => $this->bigInteger()->unsigned()->comment('创建时间'),
            'service_num'     => $this->integer()->unsigned()->comment('服务数量'),
            'gift_num'        => $this->integer()->unsigned()->comment('赠品数量'),
        ], $tableOptions);
        $this->createIndex(
            'index_course_order_id',
            '{{%member_course_service}}',
            'course_order_id'
        );
        $this->createIndex(
            'index_service_id',
            '{{%member_course_service}}',
            'service_id'
        );
        $this->createIndex(
            'index_gift_id',
            '{{%member_course_service}}',
            'gift_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_course_order_id', '{{%member_course_service}}');
        $this->dropIndex('index_service_id', '{{%member_course_service}}');
        $this->dropIndex('index_gift_id', '{{%member_course_service}}');
        $this->dropTable('{{%member_course_service}}');
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
