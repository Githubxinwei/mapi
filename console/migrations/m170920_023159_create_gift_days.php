<?php

use yii\db\Migration;

class m170920_023159_create_gift_days extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_member_complaint   会员投诉表
         * @author 李慧恩<lihuien@itsports.club>
         * @create 2017/8/22
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '卡种赠送表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%gift_day}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'days'               => $this->integer()->comment('赠送天数'),
            'gift_amount'        => $this->integer()->comment('赠送量'),
            'surplus'            => $this->integer()->comment('剩余量'),
            'type'              => $this->smallInteger()->comment('类型：1购卡，2其它'),
            'note'              => $this->string('255')->comment('备注'),
            'venue_id'           => $this->bigInteger()->unsigned()->comment('场馆id'),
            'company_id'         => $this->bigInteger()->unsigned()->comment('公司id'),
            'create_at'          => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->addColumn('{{%gift_day}}','role_id','JSON COMMENT \'角色ID存储\'');
    }

    public function down()
    {
        $this->dropTable('{{%gift_day}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170920_023159_create_gift_days cannot be reverted.\n";

        return false;
    }
    */
}
