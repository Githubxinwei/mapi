<?php

use yii\db\Migration;

class m170822_070550_create_member_complaint extends Migration
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
            $tableOptions = "COMMENT = '会员投诉表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%member_complaint}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'venue_id'           => $this->bigInteger()->unsigned()->comment('场馆id'),
            'department_id'     => $this->bigInteger()->unsigned()->comment('部门id'),
            'member_id'          => $this->bigInteger()->unsigned()->comment('会员id'),
            'complaint_type'    => $this->smallInteger()->unsigned()->comment('投诉类型'),
            'complaint_content' => $this->text(2^24-1)->comment('投诉内容'),
            'create_at'          => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%member_complaint}}',
            'venue_id'
        );
        $this->createIndex(
            'index_department_id',
            '{{%member_complaint}}',
            'department_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%member_complaint}}',
            'member_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%member_complaint}}');
    }
}
