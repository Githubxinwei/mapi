<?php

use yii\db\Migration;

class m170811_084320_create_image_management extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_image_management 图片管理表
         * @author huanghua<huanghua@itsports.club>
         * @create 2017/8/11
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '图片管理表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%image_management}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'name'               => $this->string(200)->comment('图片名'),
            'type'               => $this->smallInteger()->unsigned()->comment('状态：1团课,2私课,3场馆,4会员,5会员卡,6占位图'),
            'image_size'         => $this->integer()->unsigned()->comment('图片大小,单位KB'),
            'image_wide'         => $this->integer()->unsigned()->comment('图片宽,单位PX'),
            'image_height'       => $this->integer()->unsigned()->comment('图片高,单位PX'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
            'update_at'         => $this->bigInteger()->comment('修改时间'),
            'url'                => $this->string(255)->comment('图片名'),
            'create_id'         => $this->integer()->unsigned()->comment('创建人ID'),
            'company_id'         => $this->integer()->unsigned()->comment('公司ID'),
            'venue_id'           => $this->integer()->unsigned()->comment('场馆ID'),
        ], $tableOptions);
        $this->createIndex(
            'index_id',
            '{{%image_management}}',
            'id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%image_management}}',
            'venue_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%image_management}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id','{{%image_management}}');
        $this->dropIndex('index_venue_id','{{%image_management}}');
        $this->dropIndex('index_create_id','{{%image_management}}');
        $this->dropTable('{{%image_management}}');
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
