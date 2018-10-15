<?php

use yii\db\Migration;

class m170816_064950_create_image_management_type extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_image_management_type 图片类别表
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/8/16
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '图片类别表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%image_management_type}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'type_name'          => $this->string(200)->comment('图片类别名'),
            'create_id'          => $this->smallInteger()->unsigned()->comment('创建人id'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
            'update_at'          => $this->bigInteger()->comment('修改时间'),
            'company_id'         => $this->integer()->unsigned()->comment('公司ID'),
            'venue_id'           => $this->integer()->unsigned()->comment('场馆ID'),
        ], $tableOptions);
        $this->createIndex(
            'index_id',
            '{{%image_management_type}}',
            'id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%image_management_type}}',
            'venue_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%image_management_type}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id','{{%image_management_type}}');
        $this->dropIndex('index_venue_id','{{%image_management_type}}');
        $this->dropIndex('index_create_id','{{%image_management_type}}');
        $this->dropTable('{{%image_management_type}}');
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
