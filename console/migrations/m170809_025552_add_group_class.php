<?php

use yii\db\Migration;

class m170809_025552_add_group_class extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_group_class表
     * @author zhumengke <zhumengke@itsport.club>
     * @create 2017/8/9
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%group_class}}', 'seat_type_id', "bigint  COMMENT '座位排次id'");
    }

    public function down()
    {
        $this->dropColumn('{{%group_class}}', 'seat_type_id');
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
