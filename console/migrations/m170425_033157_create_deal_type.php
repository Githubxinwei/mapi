<?php

use yii\db\Migration;

class m170425_033157_create_deal_type extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_deal_type 合同类型表
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/4/25
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="合同类型表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%deal_type}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'create_id'      => $this->bigInteger()->unsigned()->comment('创建人id'),
            'create_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_create_id',
            '{{%deal_type}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_create_id', '{{%deal_type}}');
        $this->dropTable('{{%deal_type}}');
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
