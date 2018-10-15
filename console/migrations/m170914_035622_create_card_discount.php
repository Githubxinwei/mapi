<?php

use yii\db\Migration;

class m170914_035622_create_card_discount extends Migration
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
            $tableOptions = "COMMENT = '折扣记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%card_discount}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'limit_card_id'      => $this->bigInteger()->unsigned()->comment('通店表ID'),
            'surplus'            => $this->integer()->comment('剩余张数'),
            'discount'           => $this->double(2)->comment('折扣'),
            'create_at'          => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_limit_card_id',
            '{{%card_discount}}',
            'limit_card_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%card_discount}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170914_035622_create_card_discount cannot be reverted.\n";

        return false;
    }
    */
}
