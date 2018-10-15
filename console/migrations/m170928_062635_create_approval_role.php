<?php

use yii\db\Migration;

class m170928_062635_create_approval_role extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_approval_role  审批、抄送角色表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '审批、抄送角色表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%approval_role}}', [
            'id'               => $this->bigPrimaryKey()->comment('自增id'),
            'type'             => $this->smallInteger()->comment('类型：1审批角色，2抄送角色'),
            'approval_type_id' => $this->bigInteger()->comment('审批类型id'),
            'role_id'          => $this->bigInteger()->unsigned()->comment('角色id'),
            'employee_id'      => $this->bigInteger()->comment('员工id'),
            'company_id'       => $this->bigInteger()->unsigned()->comment('公司id'),
            'venue_id'         => $this->bigInteger()->unsigned()->comment('场馆id'),
            'department_id'    => $this->bigInteger()->unsigned()->comment('部门id'),
            'create_at'        => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_approval_type_id',
            '{{%approval_role}}',
            'approval_type_id'
        );
        $this->createIndex(
            'index_role_id',
            '{{%approval_role}}',
            'role_id'
        );
        $this->createIndex(
            'index_employee_id',
            '{{%approval_role}}',
            'employee_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%approval_role}}',
            'company_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%approval_role}}',
            'venue_id'
        );
        $this->createIndex(
            'index_department_id',
            '{{%approval_role}}',
            'department_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%approval_role}}');
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
