<?php

use yii\db\Migration;

class m170928_040312_create_approval extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_approval  审批表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '审批表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%approval}}', [
            'id'               => $this->bigPrimaryKey()->comment('自增id'),
            'name'             => $this->string(200)->comment('审批名称'),
            'polymorphic_id'   => $this->bigInteger()->unsigned()->comment('多态id'),
            'number'           => $this->bigInteger()->comment('审批编号'),
            'approval_type_id' => $this->bigInteger()->comment('审批类型id'),
            'status'           => $this->smallInteger()->comment('状态:1审批中，2已通过'),
            'create_id'        => $this->bigInteger()->unsigned()->comment('创建人id'),
            'total_progress'   => $this->smallInteger()->comment('总进度'),
            'progress'         => $this->smallInteger()->comment('当前进度'),
            'note'             => $this->text()->comment('备注'),
            'company_id'       => $this->bigInteger()->unsigned()->comment('公司id'),
            'venue_id'         => $this->bigInteger()->unsigned()->comment('场馆id'),
            'create_at'        => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'        => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_polymorphic_id',
            '{{%approval}}',
            'polymorphic_id'
        );
        $this->createIndex(
            'index_approval_type_id',
            '{{%approval}}',
            'approval_type_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%approval}}',
            'create_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%approval}}',
            'company_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%approval}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%approval}}');
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
