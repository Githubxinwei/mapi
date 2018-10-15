<?php

use yii\db\Migration;

class m170805_020118_create_information_records extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_information_records 信息记录表
         * @author huanghua <huanghua@itsports.club>
         * @create 2017/8/5
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '信息记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%information_records}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'member_id'      => $this->bigInteger()->unsigned()->notNull()->comment('会员ID'),
            'member_card_id' => $this->bigInteger()->unsigned()->comment('会员卡ID'),
            'create_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
            'note'           => $this->string(200)->comment('备注'),
            'behavior'       => $this->smallInteger()->unsigned()->notNull()->comment('行为：1延期开卡，2解冻，3冻结'),
            'create_id'      => $this->smallInteger()->unsigned()->comment('创建人id'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%information_records}}',
            'member_id'
        );
        $this->createIndex(
            'index_member_card_id',
            '{{%information_records}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%information_records}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%information_records}}');
        $this->dropIndex('index_member_card_id', '{{%information_records}}');
        $this->dropIndex('index_create_id', '{{%information_records}}');
        $this->dropTable('{{%information_records}}');
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
