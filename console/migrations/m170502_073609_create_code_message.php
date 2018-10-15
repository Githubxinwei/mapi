<?php

use yii\db\Migration;

class m170502_073609_create_code_message extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_message_code 会员卡表
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/22
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员验证码记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%message_code}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'mobile' => $this->bigInteger()->unsigned()->comment('手机号'),
            'code'   => $this->bigInteger()->unsigned()->comment('验证码'),
            'create_at'=>$this->bigInteger()->comment('创建时间')
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%message_code}}');
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
