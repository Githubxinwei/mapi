<?php

use yii\db\Migration;

class m170729_012622_create_machine_record extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_machine_record
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/7/29
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '场馆机器记录' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%machine_record}}',[
            'id'                 =>  $this->bigPrimaryKey()->comment('自增ID'),
            'ip'                 =>  $this->string(255)->comment('ip地址'),
            'machine_model'     =>  $this->string(255)->comment('机器型号'),
            'machine_type'      =>  $this->string(255)->comment('机器型号'),
            'machine_status'    =>  $this->string(255)->comment('机器状态 1:正常 2不正常'),
            "venue_id"           =>  $this->bigInteger()->unsigned()->comment('场馆id'),
            "company_id"         =>  $this->bigInteger()->comment('公司id'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%machine_record}}',
            'venue_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%machine_record}}',
            'company_id'
        );
    }
    public function down()
    {
        $this->dropTable('{{%machine_record}}');
    }
}
