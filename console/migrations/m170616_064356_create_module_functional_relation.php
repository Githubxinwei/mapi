<?php

use yii\db\Migration;

class m170616_064356_create_module_functional_relation extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_module_functional_relation 模块与功能关系表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/6/16
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="模块与功能关系表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }
        $this->createTable('{{%module_functional}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增ID'),
            'modular_id' => $this->bigInteger()->comment('模块ID'),
            'create_id' => $this->bigInteger()->comment('创建人'),
            'create_at' => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);
        $this->addColumn('{{%module_functional}}','functional_id',"json COMMENT '功能ID'");
        $this->createIndex('index_modular_id', '{{%module_functional}}', 'modular_id');
    }

    public function down()
    {

        $this->dropTable('{{%module_functional}}');
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
