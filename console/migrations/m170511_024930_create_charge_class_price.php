<?php

use yii\db\Migration;

class m170511_024930_create_charge_class_price extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_charge_class_price 私课产品价格表
         * @author 朱梦珂 <zhumengke@itsports.club>
         * @create 2017/5/11
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '私课产品价格表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%charge_class_price}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'charge_class_id' => $this->bigInteger()->unsigned()->notNull()->comment('私课ID'),
            'course_package_detail_id' => $this->bigInteger()->unsigned()->notNull()->comment('课程ID'),
            'intervalStart' => $this->integer()->unsigned()->comment('开始节数'),
            'intervalEnd' => $this->integer()->unsigned()->comment('结束节数'),
            'unitPrice' => $this->decimal()->comment('优惠单价'),
            'posPrice' => $this->decimal()->comment('pos价'),
            'create_time' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_charge_class_id',
            '{{%charge_class_price}}',
            'charge_class_id'
        );
        $this->createIndex(
            'index_course_package_detail_id',
            '{{%charge_class_price}}',
            'course_package_detail_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_charge_class_id', '{{%charge_class_price}}');
        $this->dropIndex('index_course_package_detail_id', '{{%charge_class_price}}');
        $this->dropTable('{{%charge_class_price}}');
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
