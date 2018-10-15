<?php

use yii\db\Migration;

class m170405_113058_create_card_time extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_card_time 卡时段表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/4/5
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '卡时段表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%card_time}}', [
            'id'                =>  $this->bigPrimaryKey()->comment('自增ID'),
            'card_category_id'  =>  $this->bigInteger()->unsigned()->notNull()->comment('卡种ID'),
            'start'             =>  $this->integer()->unsigned()->comment('开始时间：每天的几点'),
            'end'               =>  $this->integer()->unsigned()->comment('结束时间：每天的几点'),
            'create_at'  =>  $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'  =>  $this->bigInteger()->unsigned()->comment('更新时间'),
        ], $tableOptions);
        $this->addColumn('{{%card_time}}','day','JSON COMMENT \'日时段(json[1号,2号，3，4...])\'');
        $this->addColumn('{{%card_time}}','week','JSON COMMENT \'周时段(json[周1,周二，3，4...])\'');
        $this->addColumn('{{%card_time}}','month','JSON COMMENT \'月时段(json[1月,2月，3，4...])\'');
        $this->addColumn('{{%card_time}}','quarter','JSON COMMENT \'季度时段(json[1季度,2季度，3，4])\'');
        $this->addColumn('{{%card_time}}','year','JSON COMMENT \'年时段(json[1年，...])\'');
        $this->createIndex('index_card_category_id','{{%card_time}}','card_category_id');
    }
    /**
     * @数据库 - 回滚表 - cloud_card_time 卡时段表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/4/5
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%card_time}}');
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
