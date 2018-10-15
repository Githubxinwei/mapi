<?php

use yii\db\Migration;

class m170328_114147_create_venue_limit_times extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_venue_limit_times 进场馆次数核算表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="进场馆次数核算表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%venue_limit_times}}', [
            'id'                     => $this->bigPrimaryKey()->comment('自增ID'),
            'member_card_id'         => $this->bigInteger()->unsigned()->comment('会员卡ID'),
            'venue_id'               => $this->bigInteger()->unsigned()->comment('场馆ID'),
            'total_times'            => $this->integer()->unsigned()->comment('总次数'),
            'overplus_times'         => $this->integer()->unsigned()->comment('剩余次数'),
        ], $tableOptions);
        $this->createIndex('index_member_card_id', '{{%venue_limit_times}}', 'member_card_id');
        $this->createIndex('index_venue_id', '{{%venue_limit_times}}', 'venue_id');
    }

    public function down()
    {
        $this->dropIndex('index_member_card_id', '{{%venue_limit_times}}');
        $this->dropIndex('index_venue_id', '{{%venue_limit_times}}');
        $this->dropTable('{{%venue_limit_times}}');


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
