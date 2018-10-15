<?php

use yii\db\Migration;

class m170328_093530_create_course extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_course 课种表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="课种表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%course}}', [
            'id'         => $this->bigPrimaryKey()->comment('自增ID'),
            'pid'        => $this->integer()->unsigned()->defaultValue(0)->comment('父ID'),
            'name'       => $this->string(200)->comment('课种名'),
            'category'   => $this->string(200)->comment('类别'),
            'created_at' => $this->bigInteger()->unsigned()->comment('添加时间'),
           


        ], $tableOptions);
        $this->addColumn('{{%course}}','path','json NOT NULL COMMENT "路径(json)" ');

    }

    public function down()
    {
        
        $this->dropTable('{{%course}}');

   
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
