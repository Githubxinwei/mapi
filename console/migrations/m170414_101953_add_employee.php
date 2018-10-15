<?php

use yii\db\Migration;

class m170414_101953_add_employee extends Migration
{
    /**
     * @数据库 - 员工表 -  添加字段
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function up()
    {
        $this->addColumn('{{%employee}}', 'intro', "text  COMMENT '个人简介'");

    }
    /**
     * @数据库 - 员工表 -  回滚字段
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function down()
    {
        $this->dropColumn('{{%employee}}', 'intro');

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
