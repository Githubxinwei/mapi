<?php

use yii\db\Migration;

class m180103_065122_create_user_activity_statistics extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '用户活跃统计分析' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%user_activity_statistics}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'request_page'    => $this->smallInteger()->unsigned()->comment('请求页面类型 0:下载安装 1:团课列表 2:私教列表 3:团课详情 4:私课详情'),
            'device_type'     => $this->smallInteger()->unsigned()->comment('设备类型 1:安卓 2:ios'),
            'request_date'    => $this->string(255)->unsigned()->comment('时长'),
            'create_at'       => $this->bigInteger()->unsigned()->comment('请求时间'),
            'venue_id'        => $this->decimal()->comment('场馆id'),
            'company_id'      => $this->decimal()->comment('公司id'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%user_activity_statistics}}',
            'venue_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%user_activity_statistics}}',
            'company_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%user_activity_statistics}}');
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
