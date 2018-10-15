<?php

use yii\db\Migration;

class m170901_015131_create_mobilise extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_mobilise 调拨表
         * @author huanghua<huanghua@itsports.club>
         * @create 2017/8/1
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '调拨表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%mobilise}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'goods_id'           => $this->integer()->unsigned()->comment('商品ID'),
            'num'                => $this->integer()->unsigned()->comment('调拨数量'),
            'note'               => $this->string(200)->comment('备注'),
            'reject_note'        => $this->string(200)->comment('拒绝原因'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->comment('更新时间'),
            'create_id'          => $this->integer()->unsigned()->comment('创建人ID'),
            'company_id'         => $this->integer()->unsigned()->comment('公司ID'),
            'venue_id'           => $this->integer()->unsigned()->comment('场馆ID'),
            'store_id'        => $this->integer()->unsigned()->comment('原仓库ID'),
            'be_store_id'        => $this->integer()->unsigned()->comment('被调拨仓库ID'),
        ], $tableOptions);
        $this->createIndex(
            'index_create_id',
            '{{%mobilise}}',
            'create_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%mobilise}}',
            'venue_id'
        );
        $this->createIndex(
            'index_store_id',
            '{{%mobilise}}',
            'store_id'
        );
        $this->createIndex(
            'index_be_store_id',
            '{{%mobilise}}',
            'be_store_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_create_id','{{%mobilise}}');
        $this->dropIndex('index_venue_id','{{%mobilise}}');
        $this->dropIndex('index_store_id','{{%mobilise}}');
        $this->dropIndex('index_be_store_id','{{%mobilise}}');
        $this->dropTable('{{%mobilise}}');
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
