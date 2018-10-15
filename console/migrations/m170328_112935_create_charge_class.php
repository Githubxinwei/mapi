<?php

use yii\db\Migration;

class m170328_112935_create_charge_class extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_charge_class 收费课程表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="收费课程表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%charge_class}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'name'              => $this->string(200)->comment('名称'),
            'course_id'         => $this->bigInteger()->unsigned()->comment('课种ID'),
            'amount'            => $this->integer()->unsigned()->comment('课量'),
            'class_length'      => $this->integer()->unsigned()->comment('单节课时长'),
            'original_price'    => $this->decimal()->comment('原价'),
            'sell_price'        => $this->decimal()->comment('售价'),
            'describe'          => $this->text()->comment('介绍'),
            'pic'               => $this->text()->comment('图片'),
            'create_id'         => $this->bigInteger()->unsigned()->comment('创建人ID(员工的ID)'),
            'created_at'        => $this->bigInteger()->unsigned()->comment('创建时间'),
            'category'          => $this->integer()->comment('类型 1私教2团课'),
        ], $tableOptions);
        $this->createIndex('index_create_id', '{{%charge_class}}', 'create_id');
    }

    public function down()
    {
        $this->dropIndex('index_create_id', '{{%charge_class}}');
        $this->dropTable('{{%charge_class}}');

 
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
