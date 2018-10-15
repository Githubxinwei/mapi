<?php

use yii\db\Migration;

class m170328_095752_create_group_class extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_group_class 团课课程安排表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="团课课程安排表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group_class}}', [
            'id'           => $this->bigPrimaryKey()->comment('自增ID'),
            'start'        => $this->integer()->comment('开始上课时间'),
            'end'          => $this->integer()->comment('结束时间'),
            'class_date'   => $this->date()->comment('上课日期'),
            'created_at'   => $this->bigInteger()->unsigned()->comment('创建时间'),
            'status'       => $this->integer()->comment('状态 1正常2调课3旷课4请假'),
            'course_id'    => $this->bigInteger()->unsigned()->comment('课种ID'),
            'coach_id'     => $this->bigInteger()->unsigned()->comment('教练ID'),
            'classroom_id' => $this->bigInteger()->unsigned()->comment('教室ID'),
            'create_id'    => $this->bigInteger()->unsigned()->comment('创建人ID(员工的ID)'),
        ], $tableOptions);
        
        $this->createIndex('index_course_id', '{{%group_class}}', 'course_id');
        $this->createIndex('index_coach_id', '{{%group_class}}', 'coach_id');
        $this->createIndex('index_classroom_id', '{{%group_class}}', 'classroom_id');
        $this->createIndex('index_create_id', '{{%group_class}}', 'create_id');

    }

    public function down()
    {
        $this->dropIndex('index_course_id', '{{%group_class}}');
        $this->dropIndex('index_coach_id', '{{%group_class}}');
        $this->dropIndex('index_classroom_id', '{{%group_class}}');
        $this->dropIndex('index_create_id', '{{%group_class}}');
        $this->dropTable('{{%group_class}}');

   
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
