<?php

use yii\db\Migration;

class m170616_062726_create_jurisdiction extends Migration
{
    /**
     * @数据库 - 创建表 - auth 权限表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/16
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '权限表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%auth}}', [
            'id'        => $this->bigPrimaryKey()->comment("自增ID"),
            'role_id'   => $this->bigInteger()->unsigned()->comment("角色ID"),
            'create_id' => $this->bigInteger()->comment("创建人"),
            'create_at' => $this->bigInteger()->comment("创建时间"),
        ], $tableOptions);
        $this->addColumn('{{%auth}}','module_id',"json COMMENT '模块ID'");
        $this->addColumn('{{%auth}}','function_id',"json COMMENT '功能ID'");
    }

    public function down()
    {
        $this->dropTable('{{%auth}}');
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
