<?php

use yii\db\Migration;

class m170627_111325_add_member_deposit extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="定金表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }
        $this->createTable('{{%member_deposit}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增ID'),
            'member_id' => $this->bigInteger()->notNull()->comment('会员ID'),
            'price'     => $this->decimal(10,2)->comment('金额'),
            'voucher'   => $this->integer()->comment('券'),
            'start_time'=> $this->bigInteger()->comment('开始时间'),
            'end_time'  => $this->bigInteger()->comment('结束时间'),
            'create_id' => $this->bigInteger()->comment('创建人'),
            'create_at' => $this->bigInteger()->comment('创建时间'),
            'pay_mode' => $this->bigInteger()->comment('付款方式1.现金，2.微信，3.支付宝4.pos机'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%member_deposit}}');
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
