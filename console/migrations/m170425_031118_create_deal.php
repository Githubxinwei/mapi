<?php

use yii\db\Migration;

class m170425_031118_create_deal extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_contract 合同表添加
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/4/25
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="合同表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%deal}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'name'            => $this->string(255)->comment('名称'),
            'deal_type_id'   =>$this->bigInteger()->unsigned()->comment('类型id'),
            'intro'          => $this->text()->comment('描述'),
            'create_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
            'create_id'      => $this->bigInteger()->unsigned()->comment('创建人id'),
            'deal_number'    => $this->string(255)->unique()->comment('编号'),
        ], $tableOptions);
        $this->createIndex(
            'index_deal_type_id',
            '{{%deal}}',
            'deal_type_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%deal}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_deal_type_id', '{{%deal}}');
        $this->dropIndex('index_create_id', '{{%deal}}');
        $this->dropTable('{{%deal}}');
    }

}
