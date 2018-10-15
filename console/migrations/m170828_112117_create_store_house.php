<?php

use yii\db\Migration;

class m170828_112117_create_store_house extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_store_house 仓库管理表
         * @author huanghua<huanghua@itsports.club>
         * @create 2017/8/28
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '仓库管理表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%store_house}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'name'               => $this->string(200)->comment('仓库名'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->comment('修改时间'),
            'create_id'          => $this->integer()->unsigned()->comment('创建人ID'),
            'company_id'         => $this->integer()->unsigned()->comment('公司ID'),
            'venue_id'           => $this->integer()->unsigned()->comment('场馆ID'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%store_house}}',
            'venue_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%store_house}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_venue_id','{{%store_house}}');
        $this->dropIndex('index_create_id','{{%store_house}}');
        $this->dropTable('{{%store_house}}');
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
