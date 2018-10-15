<?php

use yii\db\Migration;

class m170328_101625_create_class_record extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_class_record  上课记录
     * @author LiHuiEn <lihuien@itsports.club>
     * @create 2017/3/28
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '上课记录表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%class_record}}', [
            'id'             => $this->bigPrimaryKey()->comment('自增ID'),
            'multiple_type'  => $this->string(200)->comment('多态类型'),
            'multiple_id'    => $this->bigInteger()->unsigned()->comment('多态ID'),
            'member_id'      => $this->bigInteger()->unsigned()->notNull()->comment('会员ID'),
            'status'         => $this->smallInteger(4)->defaultValue(1)->comment('状态1上课2请假3旷课'),
            'created_at'     => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex('index_member_id','{{%class_record}}','member_id');
    }

    public function down()
    {
        $this->dropIndex('index_member_id','{{%class_record}}');
        $this->dropTable('{{%class_record}}');
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
