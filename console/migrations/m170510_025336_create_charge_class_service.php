<?php

use yii\db\Migration;

class m170510_025336_create_charge_class_service extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_charge_class_service 私课服务表
         * @author 朱梦珂 <zhumengke@itsports.club>
         * @create 2017/5/10
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '私课服务赠品表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%charge_class_service}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'charge_class_id' => $this->bigInteger()->unsigned()->notNull()->comment('私课ID'),
            'service_id' => $this->bigInteger()->unsigned()->comment('服务ID'),
            'gift_id' => $this->bigInteger()->unsigned()->comment('赠品ID'),
            'type' => $this->smallInteger()->unsigned()->comment('类型：1服务，2赠品'),
            'category' => $this->smallInteger()->unsigned()->comment('类别：1私课，2团课'),
            'create_time' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_charge_class_id',
            '{{%charge_class_service}}',
            'charge_class_id'
        );
        $this->createIndex(
            'index_service_id',
            '{{%charge_class_service}}',
            'service_id'
        );
        $this->createIndex(
            'index_gift_id',
            '{{%charge_class_service}}',
            'gift_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_charge_class_id', '{{%charge_class_service}}');
        $this->dropIndex('index_service_id', '{{%charge_class_service}}');
        $this->dropIndex('index_gift_id', '{{%charge_class_service}}');
        $this->dropTable('{{%charge_class_service}}');
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
