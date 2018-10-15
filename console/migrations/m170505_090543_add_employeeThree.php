<?php

use yii\db\Migration;

class m170505_090543_add_employeeThree extends Migration
{
    /**
     * @数据库 - 员工表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/5
     */
    public function up()
    {
        $this->addColumn('{{%employee}}', 'alias', "varchar(255)  COMMENT '别名'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}', 'alias');
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
