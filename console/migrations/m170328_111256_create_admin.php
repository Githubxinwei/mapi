<?php

use yii\db\Migration;

class m170328_111256_create_admin extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_admin  系统管理员表
     * @author LiHuiEn <lihuien@itsports.club>
     * @create 2017/3/28
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '系统管理员表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%admin}}', [
            'id'                   => $this->bigPrimaryKey()->comment('ID自增'),
            'username'             => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key'             => $this->string(32),
            'password_hash'        => $this->string()->notNull()->comment('密码Hash'),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string()->unique()->comment('邮箱'),
            'status'               => $this->smallInteger()->defaultValue(10)->comment('状态10审核20通过'),
            'created_at'           => $this->integer()->comment('创建时间'),
            'updated_at'           => $this->integer()->comment('更新时间'),
        ], $tableOptions);
        $password = '123456';
        $password = Yii::$app->security->generatePasswordHash($password);

        $this->execute("INSERT INTO {{%admin}} (id, username, auth_key, password_hash, password_reset_token, email, status, created_at, updated_at  ) 
          VALUES(1, 'admin', '', '{$password}', NULL, 'yunyundong@itsports.club', 20, 1460097858, 1460097858);");
    }
    /**
     * @数据库 - 创建表 - cloud_admin  系统管理员表
     * @author LiHuiEn <lihuien@itsports.club>
     * @create 2017/3/28
     */
    public function down()
    {
        $this->dropTable('{{%admin}}');
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
