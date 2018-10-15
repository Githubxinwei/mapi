<?php

use yii\db\Migration;

class m170928_064633_create_approval_details extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_approval_details   审核详情表
         * @author 李慧恩<lihuien@itsports.club>
         * @create 2017/8/22
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '审核详情表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%approval_details}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'approval_id'        => $this->bigInteger()->unsigned()->comment('审核表ID'),
            'status'             => $this->smallInteger()->comment('状态：1.审批中,2.已同意，3已拒绝，4已撤销'),
            'approver_id'        => $this->bigInteger()->unsigned()->comment('审批人'),
            'describe'           => $this->text()->comment('审批描述'),
            'approval_process_id'=> $this->bigInteger()->unsigned()->comment('审批流程ID'),
            'create_at'          => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex('index_approval_id', '{{%approval_details}}', 'approval_id');
        $this->createIndex('index_approver_id', '{{%approval_details}}', 'approver_id');
        $this->createIndex('index_approval_process_id', '{{%approval_details}}', 'approval_process_id');

    }

    public function down()
    {
        $this->dropTable('{{%approval_details}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170928_064633_create_approval_details cannot be reverted.\n";

        return false;
    }
    */
}
