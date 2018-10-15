<?php

use yii\db\Migration;

class m171213_064835_create_member_card_time extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_member_card_time 会员卡时段表
     * @author zhumengke <zhumengke@itsport.club>
     * @create 2017/12/13
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员卡时段表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%member_card_time}}', [
            'id'             => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id' => $this->bigInteger()->unsigned()->notNull()->comment('会员卡ID'),
            'start'          => $this->integer()->unsigned()->comment('开始时间:每天的几点'),
            'end'            => $this->integer()->unsigned()->comment('结束时间:每天的几点'),
            'create_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'      => $this->bigInteger()->unsigned()->comment('更新时间'),
        ], $tableOptions);
        $this->addColumn('{{%member_card_time}}','day','JSON COMMENT "日(json[1号,2号...])"');
        $this->addColumn('{{%member_card_time}}','week','JSON COMMENT "周(json[周一,周二...])"');
        $this->addColumn('{{%member_card_time}}','month','JSON COMMENT "月(json[1月,2月...])"');
        $this->addColumn('{{%member_card_time}}','quarter','JSON COMMENT "季(json[1季度,2季度...])"');
        $this->addColumn('{{%member_card_time}}','year','JSON COMMENT "年(json[1年...])"');
        $this->createIndex('index_member_card_id','{{%member_card_time}}','member_card_id');
    }

    public function down()
    {
        $this->dropTable('{{%member_card_time}}');
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
