<?php

use yii\db\Migration;

class m170518_035209_add_employeeFour extends Migration
{
    /**
     * @数据库 - 员工表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/18
     */
    public function up()
    {
        $this->addColumn('{{%employee}}', 'work_time', "bigint(20) UNSIGNED COMMENT '从业时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}', 'work_time');
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
