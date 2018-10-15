<?php

use yii\db\Migration;

class m170328_080649_create_organization extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_organization 组织架构表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="组织架构表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%organization}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'pid'             => $this->bigInteger()->unsigned()->defaultValue(0)->comment('父ID'),
            'name'            => $this->string(200)->comment('名称'),
            'style'           => $this->integer()->comment('类型 1公司2场馆3部门'),
            'created_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),


        ], $tableOptions);
        $this->addColumn('{{%organization}}','params',"json NOT NULL COMMENT '参数 自定义属性 json([免费课量=>10,基础课量=>10])' ");
    }

    public function down()
    {

        $this->dropTable('{{%organization}}');

      
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
