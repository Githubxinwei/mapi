<?php

use yii\db\Migration;

class m171013_021122_create_extension_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '延期记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%extension_record}}',[
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            "course_order_id"    => $this->bigInteger()->comment('课程订单Id'),
            "course_name"        => $this->string(200)->comment('课程名称'),
            "course_num"         => $this->smallInteger()->unsigned()->comment('课程节数'),
            "postpone_day"       => $this->smallInteger()->unsigned()->comment('延期天数'),
            "due_day"            => $this->integer()->unsigned()->comment('到期日期'),
            "remark"             => $this->string(200)->comment('延期备注'),
            'create_at'          => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_course_order_id',
            '{{%extension_record}}',
            'course_order_id'
        ); 
    }

    public function down()
    {
        $this->dropTable("{{%extension_record}}");
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
