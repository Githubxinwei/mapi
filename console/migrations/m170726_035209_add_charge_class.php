<?php

use yii\db\Migration;

class m170726_035209_add_charge_class extends Migration
{
    /**
     * @数据库 - 收费课程表 - 新增课程类型
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/26
     */
    public function up()
    {
        $this->addColumn('{{%charge_class}}','course_type',"smallint COMMENT '课程类型:1购买,2赠送'");
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}','course_type');
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
