<?php

use yii\db\Migration;

class m170627_060657_create_apply_record extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_apply_record  申请记录表
         * @author 朱梦珂 <zhumengke@itsports.club>
         * @create 2017/6/27
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '申请记录表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%apply_record}}', [
            'id'               => $this->bigPrimaryKey()->comment('自增ID'),
            'apply_id'         => $this->bigInteger()->unsigned()->comment('申请公司id'),
            'be_apply_id'      => $this->bigInteger()->unsigned()->comment('被申请公司id'),
            'start_apply'      => $this->bigInteger()->unsigned()->comment('通店开始日期'),
            'end_apply'        => $this->bigInteger()->unsigned()->comment('通店结束日期'),
            'status'            => $this->smallInteger()->comment('状态：1已通过；2等待通过；3未通过；4取消；5过期；'),
            'not_apply_length' => $this->integer()->unsigned()->comment('不可申请时长'),
            'note'              => $this->text()->unsigned()->comment('备注'),
            'create_id'         => $this->bigInteger()->unsigned()->comment('操作人id'),
            'create_at'         => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'         => $this->bigInteger()->unsigned()->comment('更新时间'),
        ], $tableOptions);
        $this->createIndex('index_apply_id','{{%apply_record}}','apply_id');
        $this->createIndex('index_be_apply_id','{{%apply_record}}','be_apply_id');
        $this->createIndex('index_create_id','{{%apply_record}}','create_id');
    }

    public function down()
    {
        $this->dropTable('{{%apply_record}}');
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
