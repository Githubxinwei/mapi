<?php

use yii\db\Migration;

class m170425_064628_add_employee_two extends Migration
{
    /**
     * @数据库 - 员工表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/25
     */
    public function up()
    {
        $this->addColumn('{{%employee}}', 'class_hour', "int(11) COMMENT '课时'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}',"class_hour");
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
