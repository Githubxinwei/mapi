<?php

use yii\db\Migration;

class m170328_095542_create_cloud_transfer_record extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_transfer_record 转让记录表
         * @author Zhu Mengke <zhumengke@itsports.club>
         * @create 2017/3/28
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '转让记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%transfer_record}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id' => $this->bigInteger()->unsigned()->notNull()->comment('会员卡id'),

            'to_member_id' => $this->bigInteger()->unsigned()->notNull()->comment('会员id'),

            'from_member_id' => $this->bigInteger()->unsigned()->notNull()->comment('转让人id'),

            'transfer_time' => $this->bigInteger()->unsigned()->notNull()->comment('转让时间'),

            'times' => $this->smallInteger()->unsigned()->comment('剩余次数'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_card_id',
            '{{%transfer_record}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_to_member_id',
            '{{%transfer_record}}',
            'to_member_id'
        );
        $this->createIndex(
            'index_from_member_id',
            '{{%transfer_record}}',
            'from_member_id'
        );
        $this->addColumn('{{%transfer_record}}','path','json NOT NULL COMMENT "卡的转让经历(json)" ');
    }

    public function down()
    {
        $this->dropIndex('index_member_card_id', '{{%transfer_record}}');
        $this->dropIndex('index_to_member_id', '{{%transfer_record}}');
        $this->dropIndex('index_from_member_id', '{{%transfer_record}}');
        $this->dropTable('{{%transfer_record}}');
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
