<?php

use yii\db\Migration;

class m170413_013252_create_config extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_config 配置表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/4/13
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="配置表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%config}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'key'               => $this->string(200)->comment('名称'),
            'value'             => $this->string(200)->comment('值'),
            'type'              => $this->string(200)->comment('类型'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%config}}');
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
