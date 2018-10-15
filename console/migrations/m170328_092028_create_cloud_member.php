<?php

use yii\db\Migration;

class m170328_092028_create_cloud_member extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_member 会员基本信息表
         * @author Zhu Mengke <zhumengke@itsports.club>
         * @create 2017/3/28
         * @inheritdoc
         */

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员基本信息表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'username' => $this->string(200)->notNull()->comment('用户名'),
            'password' => $this->string(200)->notNull()->comment('密码'),
            'mobile' => $this->string(200)->notNull()->comment('手机号'),
            'register_time' => $this->bigInteger()->unsigned()->notNull()->comment('注册时间'),
            'password_token' => $this->string(200),

            'hash' => $this->string(200),
            'update_at' => $this->bigInteger()->unsigned()->comment('修改时间'),
            'last_time' => $this->bigInteger()->unsigned()->comment('最近一次登录时间'),
            'last_fail_login_time' => $this->bigInteger()->unsigned()->comment('上次登录失败时间'),
            'times' => $this->integer()->unsigned()->comment('登录失败次数'),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('状态：0待审核，1正常，2禁用'),

            'lock_time' => $this->bigInteger()->unsigned()->comment('锁定时长'),
        ], $tableOptions);
        $this->addColumn('{{%member}}','params','json COMMENT "扩展(json)" ');
    }

    public function down()
    {
        $this->dropTable('{{%member}}');
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
