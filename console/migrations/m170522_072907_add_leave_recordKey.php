<?php

use yii\db\Migration;

class m170522_072907_add_leave_recordKey extends Migration
{
    /**
     * @数据库 - 请假记录表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%leave_record}}',
            'id'
        );
        $this->createIndex(
            'index_leave_employee_id',
            '{{%leave_record}}',
            'leave_employee_id'
        );
        $this->createIndex(
            'index_is_approval_id',
            '{{%leave_record}}',
            'is_approval_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%leave_record}}');
        $this->dropIndex('index_leave_employee_id', '{{%leave_record}}');
        $this->dropIndex('index_is_approval_id', '{{%leave_record}}');

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
