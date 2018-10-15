<?php

use yii\db\Migration;

class m180104_063330_create_charge_group_class extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '私教多人课程安排表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%charge_group_class}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'start'           => $this->bigInteger()->comment('开始时间'),
            'end'             => $this->bigInteger()->comment('结束时间'),
            'class_date'      => $this->date()->comment('上课日期'),
            'created_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
            'status'          => $this->smallInteger()->comment('状态:1正常2预约3课中4下课5取消'),
            'class_number_id' => $this->bigInteger()->unsigned()->comment('私课编号id'),
            'coach_id'        => $this->bigInteger()->unsigned()->comment('教练id'),
            'company_id'      => $this->bigInteger()->unsigned()->comment('公司id'),
            'venue_id'        => $this->bigInteger()->unsigned()->comment('场馆id'),
        ], $tableOptions);

        $this->createIndex('index_class_number_id', '{{%charge_group_class}}', 'class_number_id');
        $this->createIndex('index_company_id', '{{%charge_group_class}}', 'company_id');
        $this->createIndex('index_venue_id', '{{%charge_group_class}}', 'venue_id');
    }

    public function down()
    {
        $this->dropIndex('index_class_number_id', '{{%charge_group_class}}');
        $this->dropIndex('index_company_id', '{{%charge_group_class}}');
        $this->dropIndex('index_venue_id', '{{%charge_group_class}}');
        $this->dropTable('{{%charge_group_class}}');
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
