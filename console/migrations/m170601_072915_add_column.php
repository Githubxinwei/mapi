<?php

use yii\db\Migration;

class m170601_072915_add_column extends Migration
{
    /**
     * @数据库 - 添加字段 - 会员表和约课表
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/1
     */
    public function up()
    {
        $this->addColumn('{{%member}}','is_employee',"smallint(6) COMMENT '是不是员工：1 是'");
        $this->addColumn('{{%about_class}}','employee_id',"bigint(20) COMMENT '员工id'");
    }
    /**
     * @数据库 - 回滚字段 - 会员表和约课表
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/1
     */
    public function down()
    {
        $this->dropColumn('{{%member}}','is_employee');
        $this->dropColumn('{{%about_class}}','employee_id');
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
