<?php

use yii\db\Migration;

class m170728_071329_create_scan_code extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_scan_code
     * @author houkaixin<houkaixin@itsport.club>
     * @create 2017/7/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '扫码记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%scan_code_record}}', [
            'id'                 =>  $this->bigPrimaryKey()->comment('自增ID'),
            'member_id'         =>  $this->bigInteger()->unsigned()->comment('会员id'),
            'create_at'         =>  $this->bigInteger()->unsigned()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_id',
            '{{%scan_code_record}}',
            'member_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%scan_code_record}}');
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
