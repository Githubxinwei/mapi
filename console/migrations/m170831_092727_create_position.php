<?php

use yii\db\Migration;

class m170831_092727_create_position extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_member_complaint   会员投诉表
         * @author houkaixin<houkaixin@itsports.club>
         * @create 2017/8/22
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '员工职位表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%position}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'name'               => $this->string(200)->comment('职位名称'),
            'note'               => $this->string(200)->comment('备注'),
            'venue_id'           => $this->bigInteger()->unsigned()->comment('场馆id'),
            'department_id'      => $this->bigInteger()->unsigned()->comment('部门id'),
            'company_id'         => $this->bigInteger()->unsigned()->comment('公司id'),
            'create_at'          => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%position}}',
            'venue_id'
        );
        $this->createIndex(
            'index_department_id',
            '{{%position}}',
            'department_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%position}}',
            'company_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%position}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170831_092727_create_position cannot be reverted.\n";

        return false;
    }
    */
}
