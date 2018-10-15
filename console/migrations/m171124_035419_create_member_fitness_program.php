<?php

use yii\db\Migration;

class m171124_035419_create_member_fitness_program extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_member_fitness_program 会员健身计划表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/24
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员健身计划表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%member_fitness_program}}', [
            'id'            => $this->bigPrimaryKey()->comment('自增id'),
            'member_id'     => $this->bigInteger()->unsigned()->comment('会员id'),
            'target_weight' => $this->integer()->unsigned()->comment('目标体重'),
            'fitness_id'    => $this->bigInteger()->unsigned()->comment('健身目标id'),
            'diet_id'       => $this->bigInteger()->unsigned()->comment('饮食计划id'),
            'create_at'     => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'     => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%member_fitness_program}}',
            'member_id'
        );
        $this->createIndex(
            'index_fitness_id',
            '{{%member_fitness_program}}',
            'fitness_id'
        );
        $this->createIndex(
            'index_diet_id',
            '{{%member_fitness_program}}',
            'diet_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%member_fitness_program}}');
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
