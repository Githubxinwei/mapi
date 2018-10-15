<?php

use yii\db\Migration;

class m171124_035305_create_fitness_diet extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_fitness_diet 健身饮食表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/24
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '健身饮食表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%fitness_diet}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增Id'),
            'type'      => $this->smallInteger()->comment('类型：1.健身目标,2.饮食计划'),
            'name'      => $this->string()->unsigned()->comment('名称'),
            'content'   => $this->text()->comment('内容'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at' => $this->bigInteger()->unsigned()->comment('修改时间'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable("{{%fitness_diet}}");
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
