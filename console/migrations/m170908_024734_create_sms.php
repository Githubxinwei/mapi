<?php

use yii\db\Migration;

class m170908_024734_create_sms extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_sms_record 短信记录表
     * @author huanghua<huanghua@itsports.club>
     * @create 2017/9/8
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '短信管理表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%sms_record}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'member_id'          => $this->integer()->unsigned()->comment('会员Id'),
            'mobile'             => $this->string(200)->comment('手机号'),
            'send_code'          => $this->string(200)->comment('发送码'),
            'status'             => $this->smallInteger()->unsigned()->comment('状态:1已发送2发送失败'),
            'type'               => $this->smallInteger()->unsigned()->comment('类型:1售卡2生日3上下课'),
            'content'            => $this->string(200)->comment('内容'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
            'create_id'          => $this->integer()->unsigned()->comment('创建人ID'),
            'company_id'         => $this->integer()->unsigned()->comment('公司ID'),
            'venue_id'           => $this->integer()->unsigned()->comment('场馆ID'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%sms_record}}',
            'member_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%sms_record}}',
            'venue_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%sms_record}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_id','{{%sms_record}}');
        $this->dropIndex('index_venue_id','{{%sms_record}}');
        $this->dropIndex('index_create_id','{{%sms_record}}');
        $this->dropTable('{{%sms_record}}');
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
