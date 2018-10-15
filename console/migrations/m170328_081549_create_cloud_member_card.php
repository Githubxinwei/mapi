<?php

use yii\db\Migration;

class m170328_081549_create_cloud_member_card extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_member_card 会员卡表
         * @author Zhu Mengke <zhumengke@itsports.club>
         * @create 2017/3/28
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员卡表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_card}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'card_category_id' => $this->bigInteger()->unsigned()->notNull()->unique()->comment('卡种ID'),

            'card_number' => $this->string(200)->notNull()->unique()->comment('卡号'),
            'member_id' => $this->bigInteger()->unsigned()->notNull()->unique()->comment('会员ID'),

            'create_at' => $this->bigInteger()->unsigned()->comment('开卡时间'),
            'amount_money' => $this->decimal()->comment('金额'),

            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(4)->comment('状态：1正常，2异常，3冻结，4未激活'),
            'active_time' => $this->bigInteger()->unsigned()->comment('激活时间'),
            'payment_type' => $this->smallInteger()->unsigned()->notNull()->comment('付款类型：1全款，2分期'),
            'is_complete_pay' => $this->boolean()->notNull()->comment('是否完成付款'),
            'pay_times' => $this->smallInteger()->unsigned()->comment('付款次数'),
            'paid_months' => $this->smallInteger()->unsigned()->comment('已付月数'),

            'total_times' => $this->integer()->unsigned()->comment('总次数（次卡）'),
            'consumption_times' => $this->integer()->unsigned()->comment('消费次数'),
            'invalid_time' => $this->bigInteger()->unsigned()->notNull()->comment('失效时间'),
            'balance' => $this->decimal()->comment('余额'),
            'is_share' => $this->boolean()->comment('分享信息（针对会员带人）'),
            'frozen_start_time' => $this->bigInteger()->unsigned()->comment('冻结开始时间'),

            'frozen_end_time' => $this->bigInteger()->unsigned()->comment('冻结结束时间'),
            'level' => $this->integer()->unsigned()->notNull()->comment('等级'),
        ], $tableOptions);
        $this->createIndex(
            'index_card_category_id',
            '{{%member_card}}',
            'card_category_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%member_card}}',
            'member_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_card_category_id', '{{%member_card}}');
        $this->dropIndex('index_member_id', '{{%member_card}}');
        $this->dropTable('{{%member_card}}');
    }
}
