<?php

use yii\db\Migration;

class m170705_070147_create_member_base extends Migration
{
    public function up()
    {
    /**
     * @数据库 - 创建表 - cloud_member_base  会员验证设置表
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/27
     */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员验证设置表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%member_base}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增ID'),
            'member_id'         => $this->bigInteger()->unsigned()->comment('会员id'),
            'qq_open_id'        => $this->string(255)->comment('qq登录唯一id'),
            'wx_open_id'        => $this->string(255)->comment('微信登录唯一id'),
            'wb_open_id'        => $this->string(255)->comment('微博登录唯一id'),
            'note'              => $this->text()->unsigned()->comment('备注'),
            'create_at'         => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_at'         => $this->bigInteger()->unsigned()->comment('更新时间'),
        ], $tableOptions);
        $this->createIndex('index_member_id','{{%member_base}}','member_id');
    }

    public function down()
    {
        $this->dropTable('{{%member_base}}');
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
