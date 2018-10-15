<?php

use yii\db\Migration;

class m170728_070843_add_seat extends Migration
{
    /**
     * @数据库 - 座位表 - 新增字段
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/28
     */
    public function up()
    {
        $this->addColumn('{{%seat}}','rows',"int COMMENT '排数'");
        $this->addColumn('{{%seat}}','columns',"int COMMENT '列数'");
        $this->addColumn('{{%seat}}','seat_type_id',"bigint COMMENT '座位类型id'");
    }

    public function down()
    {
        $this->dropColumn('{{%seat}}','rows');
        $this->dropColumn('{{%seat}}','columns');
        $this->dropColumn('{{%seat}}','seat_type_id');
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
