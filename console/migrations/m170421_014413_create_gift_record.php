<?php

use yii\db\Migration;

class m170421_014413_create_gift_record extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_gift_record 赠品记录表
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/4/21
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '赠品记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%gift_record}}', [
            'id' => $this->bigPrimaryKey()->notNull()->comment('自增ID'),
            'member_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('会员ID'),
            'member_card_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('会员卡ID'),
            'service_pay_id' => $this->bigInteger()->unsigned()->unique()->notNull()->comment('收费项目ID'),
            'num' => $this->smallInteger()->unsigned()->comment('数量'),
            'status' => $this->smallInteger()->unsigned()->defaultValue(1)->comment('状态：1未领取，2已领取'),

        ], $tableOptions);

        $this->createIndex(
            'index_member_id',
            '{{%gift_record}}',
            'member_id'
        );
        $this->createIndex(
            'index_member_card_id',
            '{{%gift_record}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_service_pay_id',
            '{{%gift_record}}',
            'service_pay_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%gift_record}}');
        $this->dropIndex('index_member_card_id', '{{%gift_record}}');
        $this->dropIndex('index_service_pay_id', '{{%gift_record}}');
        $this->dropTable('{{%gift_record}}');
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
