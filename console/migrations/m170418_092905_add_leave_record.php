<?php

use yii\db\Migration;

class m170418_092905_add_leave_record extends Migration
{
    /**
     * @数据库 - 请假记录表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/18
     */
    public function up()
    {
        $this->addColumn('{{%leave_record}}', 'is_approval_id', "bigint(20) UNSIGNED COMMENT '批准人ID'");
        $this->addColumn('{{%leave_record}}', 'leave_length',   "integer(11) UNSIGNED COMMENT '请假时长'");
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}', 'is_approval_id');
        $this->dropColumn('{{%leave_record}}', 'leave_length');
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
