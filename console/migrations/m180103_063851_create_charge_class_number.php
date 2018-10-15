<?php

use yii\db\Migration;

class m180103_063851_create_charge_class_number extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '私课编号表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%charge_class_number}}', [
            'id'               => $this->bigPrimaryKey()->comment('自增ID'),
            'charge_class_id'  => $this->bigInteger()->unsigned()->comment('私课表id	'),
            'class_people_id'  => $this->bigInteger()->unsigned()->comment('私课人数区间id	'),
            'class_number'     => $this->string()->comment('私课编号'),
            'sell_number'      => $this->integer()->unsigned()->comment('售卖数量'),
            'surplus'          => $this->integer()->unsigned()->comment('剩余数量'),
            'total_class_num'  => $this->integer()->unsigned()->comment('总节数'),
            'attend_class_num' => $this->integer()->unsigned()->comment('已上节数'),
            'venue_id'         => $this->bigInteger()->unsigned()->comment('场馆id	'),
            'company_id'       => $this->bigInteger()->unsigned()->comment('公司id	'),
            'valid_start_time' => $this->bigInteger()->unsigned()->comment('有效期开始时间'),
            'valid_time'       => $this->integer()->unsigned()->comment('产品有效期(天)'),
            'sale_num'         => $this->integer()->unsigned()->comment('售卖课程套数'),
            'surplus_sale_num'         => $this->integer()->unsigned()->comment('售卖课程剩余套数'),
        ], $tableOptions);
        $this->createIndex(
            'index_charge_class_id',
            '{{%charge_class_number}}',
            'charge_class_id'
        );
        $this->createIndex(
            'index_class_people_id',
            '{{%charge_class_number}}',
            'class_people_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%charge_class_number}}',
            'venue_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%charge_class_number}}',
            'company_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_charge_class_id', '{{%charge_class_number}}');
        $this->dropIndex('index_class_people_id', '{{%charge_class_number}}');
        $this->dropIndex('index_venue_id', '{{%charge_class_number}}');
        $this->dropIndex('index_company_id', '{{%charge_class_number}}');
        $this->dropTable('{{%charge_class_number}}');
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
