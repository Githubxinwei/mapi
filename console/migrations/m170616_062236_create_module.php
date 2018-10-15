<?php

use yii\db\Migration;

class m170616_062236_create_module extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_module 模块表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/6/16
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="模块表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }
        $this->createTable('{{%module}}', [
            'id'        => $this->bigPrimaryKey()->comment('自增ID'),
            'name'      => $this->string(200)->notNull()->comment('菜单名'),
            'e_name'    => $this->string(200)->notNull()->comment('菜单英文名'),
            'level'     => $this->integer()->comment('等级'),
            'pid'       => $this->bigInteger()->unsigned()->defaultValue(0)->comment('父ID'),
            'note'      => $this->string(200)->comment('说明'),
            'create_id' => $this->bigInteger()->comment('创建人'),
            'create_at' => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%module}}');
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
