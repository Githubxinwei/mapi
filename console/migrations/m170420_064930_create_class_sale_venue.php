<?php

use yii\db\Migration;

class m170420_064930_create_class_sale_venue extends Migration
{
    /**
     * @数据库 - 修改表 - class_sale_venue
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/4/20
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '课种售卖场馆表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%class_sale_venue}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'charge_class_id' => $this->bigInteger()->unsigned()->notNull()->comment('私课ID'),
            'venue_id' => $this->bigInteger()->unsigned()->notNull()->comment('场馆ID'),
            'sale_num' => $this->integer()->unsigned()->comment('售卖数量'),
            'sale_start_time' => $this->bigInteger()->unsigned()->comment('售卖开始时间'),
            'sale_end_time' => $this->bigInteger()->unsigned()->comment('售卖结束时间'),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(1)->comment('状态：1私课，2团课'),
        ], $tableOptions);
        $this->createIndex(
            'index_charge_class_id',
            '{{%class_sale_venue}}',
            'charge_class_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%class_sale_venue}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_charge_class_id', '{{%class_sale_venue}}');
        $this->dropIndex('index_venue_id', '{{%class_sale_venue}}');
        $this->dropTable('{{%class_sale_venue}}');
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
