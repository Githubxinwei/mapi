<?php

use yii\db\Migration;

class m170328_093040_create_class_server extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_card_category_type 课程套餐表添加
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '课程套餐表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%class_server}}', [
            'id'                 =>  $this->bigPrimaryKey()->comment('自增ID'),
            'server_name'       =>  $this->string(200)->unique()->notNull()->comment('套餐名'),
            'create_at'         =>  $this->bigInteger()->unsigned()->notNull()->comment('添加时间'),
        ], $tableOptions);
    }
    /**
     * @数据库 - 创建表 - cloud_card_category_type 课程套餐表
     * @author Huang Pengju<huangpengju@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%class_server}}');
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
