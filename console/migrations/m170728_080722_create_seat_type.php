<?php

use yii\db\Migration;

class m170728_080722_create_seat_type extends Migration
{
    /**
     * @数据库 - 创建表 - 座位排次类型表
     * @author zhumengke <zhumengke@itsport.club>
     * @create 2017/7/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="座位排次类型表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%seat_type}}', [
            'id'             => $this->bigPrimaryKey()->comment('自增ID'),
            'name'           => $this->string()->comment('座位排次名称'),
            'classroom_id'  => $this->bigInteger()->unsigned()->comment('教室id'),
            'total_rows'    => $this->integer()->unsigned()->comment('总排数'),
            'total_columns' => $this->integer()->unsigned()->comment('总列数'),
            'create_at'     => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'     => $this->bigInteger()->unsigned()->comment('修改时间'),
            'company_id'    => $this->bigInteger()->unsigned()->comment('公司id'),
            'venue_id'      => $this->bigInteger()->unsigned()->comment('场馆id'),
        ], $tableOptions);
        $this->createIndex(
            'index_classroom_id',
            '{{%seat_type}}',
            'classroom_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%seat_type}}',
            'company_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%seat_type}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%seat_type}}');
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
