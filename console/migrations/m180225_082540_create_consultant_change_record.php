<?php

use yii\db\Migration;

class m180225_082540_create_consultant_change_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="会籍顾问变更记录表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%consultant_change_record}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增Id'),
            'member_id'       => $this->bigInteger()->unsigned()->comment('会员Id'),
            'create_id'       => $this->bigInteger()->unsigned()->comment('操作人Id'),
            'created_at'      => $this->bigInteger()->unsigned()->comment('创建时间'),
            'consultant_id'   => $this->bigInteger()->unsigned()->comment('会籍顾问Id'),
            'venue_id'        => $this->smallInteger()->unsigned()->comment('场馆Id'),
            'company_id'      => $this->smallInteger()->unsigned()->comment('公司Id'),

        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%consultant_change_record}}',
            'member_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%consultant_change_record}}',
            'create_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%consultant_change_record}}',
            'venue_id'
        );
        $this->createIndex(
            'index_consultant_id',
            '{{%consultant_change_record}}',
            'consultant_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%consultant_change_record}}');
        $this->dropIndex('index_create_id', '{{%consultant_change_record}}');
        $this->dropIndex('index_venue_id', '{{%consultant_change_record}}');
        $this->dropIndex('index_consultant_id','{{%consultant_change_record}}');
        $this->dropTable('{{%consultant_change_record}}');
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
