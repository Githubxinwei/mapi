<?php

use yii\db\Migration;

class m171124_035538_create_fitness_program_send extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_fitness_program_send 健身计划发送表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/24
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '健身计划发送表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%fitness_program_send}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增id'),
            'member_id' => $this->bigInteger()->unsigned()->comment('会员id'),
            'name'      => $this->string()->comment('名称'),
            'content'   => $this->text()->comment('内容'),
            'send_time' => $this->bigInteger()->unsigned()->comment('发送时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%fitness_program_send}}',
            'member_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%fitness_program_send}}');
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
