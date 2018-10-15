<?php

use yii\db\Migration;

class m170918_065143_create_miss_about_set extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '团课爽约设置表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%miss_about_set}}',[
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            "course_type"       => $this->smallInteger()->unsigned()->comment('课程类型 1:团课 2:私课'),
            "freeze_way"        => $this->smallInteger()->unsigned()->comment('冻结方式 1:当月冻结 2:自定义冻结天数'),
            "miss_about_times" => $this->integer()->unsigned()->comment('每月爽约次数'),
            "freeze_day"        => $this->integer()->unsigned()->comment('冻结天数'),
            "punish_money"      => $this->decimal(10,2)->comment('处罚金额'),
            'company_id'        => $this->integer()->unsigned()->comment('公司ID'),
            'venue_id'          => $this->integer()->unsigned()->comment('场馆ID'),
            'create_at'         => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%miss_about_set}}',
            'venue_id'
        );
        $this->createIndex(
            'index_company_id',
            '{{%miss_about_set}}',
            'company_id'
        );
    }

    public function down()
    {
        $this->dropTable("{{%miss_about_set}}");
    }
}
