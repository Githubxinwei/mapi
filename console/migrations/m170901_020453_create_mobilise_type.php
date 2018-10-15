<?php

use yii\db\Migration;

class m170901_020453_create_mobilise_type extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_mobilise_type 调拨记录表
         * @author huanghua<huanghua@itsports.club>
         * @create 2017/8/1
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '调拨记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%mobilise_type}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'mobilise_id'        => $this->integer()->unsigned()->comment('调拨ID'),
            'type'               => $this->integer()->unsigned()->comment('状态1.已申请2.已通过3.已调拨'),
            'note'               => $this->string(200)->comment('备注'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->comment('更新时间'),
            'create_id'          => $this->integer()->unsigned()->comment('创建人ID'),
        ], $tableOptions);
        $this->createIndex(
            'index_mobilise_id',
            '{{%mobilise_type}}',
            'mobilise_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%mobilise_type}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_mobilise_id','{{%mobilise_type}}');
        $this->dropIndex('index_create_id','{{%mobilise_type}}');
        $this->dropTable('{{%mobilise_type}}');
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
