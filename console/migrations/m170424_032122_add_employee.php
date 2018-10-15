<?php

use yii\db\Migration;

class m170424_032122_add_employee extends Migration
{
    /**
     * @数据库 - 员工表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/24
     */
    public function up()
    {
        $this->addColumn('{{%employee}}', 'level', "int(11) COMMENT '等级:0新员工1低级2中级3高级'");
        $this->addColumn('{{%employee}}', 'pic', "varchar(200) COMMENT '头像'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}',"level");
        $this->dropColumn('{{%employee}}',"pic");
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
