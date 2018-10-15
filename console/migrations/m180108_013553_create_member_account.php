<?php

use yii\db\Migration;

/**
 * Class m180108_013553_create_member_account
 */
class m180108_013553_create_member_account extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '会员账户表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%member_account}}', [
            'id'          => $this->bigPrimaryKey()->comment('自增ID'),
            'username'    => $this->string(200)->comment('用户名'),
            'password'    => $this->string(255)->comment('密码'),
            'mobile'      => $this->string(200)->comment('手机号'),
            'last_time'   => $this->bigInteger()->comment('最后登录时间'),
            'company_id'   => $this->bigInteger()->comment('公司ID'),
            'create_at'   => $this->bigInteger()->comment('常见时间'),
        ], $tableOptions);
        $this->addColumn('{{%member}}', 'member_account_id', "BIGINT(20) COMMENT '账户表'");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%member_account}}");
        $this->dropColumn('{{%member}}', 'member_account_id');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180108_013553_create_member_account cannot be reverted.\n";

        return false;
    }
    */
}
