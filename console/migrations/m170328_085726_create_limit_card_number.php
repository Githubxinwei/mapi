<?php

use yii\db\Migration;

class m170328_085726_create_limit_card_number extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_card_category_type 通店和限发量表添加
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '通店和限发量表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%limit_card_number}}', [
            'id'                 =>  $this->bigPrimaryKey()->comment('自增ID'),
            'card_category_id'  =>  $this->bigInteger()->unsigned()->notNull()->comment('卡种ID'),
            'venue_id'           =>  $this->bigInteger()->unsigned()->notNull()->comment('场馆ID'),
            'times'              =>  $this->integer()->unsigned()->null()->defaultValue(0)->comment('进场次数'),
            'limit'              =>  $this->integer()->unsigned()->null()->defaultValue(0)->comment('卡限发量'),
            'level'              =>  $this->smallInteger()->unsigned()->null()->defaultValue(0)->comment('同一卡种，场馆不同等级不同'),
        ], $tableOptions);
        $this->createIndex(
            'index_card_category_id',
            '{{%limit_card_number}}',
            'card_category_id'
        );
    }
    /**
     * @数据库 - 创建表 - cloud_card_category_type 通店和限发量表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%limit_card_number}}');
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
