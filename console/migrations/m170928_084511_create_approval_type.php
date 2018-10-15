<?php

use yii\db\Migration;

class m170928_084511_create_approval_type extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_approval_process  审批类型表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '审批类型表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%approval_type}}', [
            'id'         => $this->bigPrimaryKey()->comment('自增id'),
            'type'       => $this->string()->comment('审批类型'),
            'company_id' => $this->bigInteger()->unsigned()->comment('公司id'),
            'venue_id'   => $this->bigInteger()->unsigned()->comment('场馆id'),
            'create_at'  => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_company_id',
            '{{%approval_type}}',
            'company_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%approval_type}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%approval_type}}');
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
