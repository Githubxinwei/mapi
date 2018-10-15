<?php

use yii\db\Migration;

class m170328_111807_create_charge_course_flow extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_charge_course_flow 收费课程流水表添加
     * @author Huang hua<huanghua@itsport.club>
     * @create 2017/3/28
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'COMMENT="收费课程流水表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%charge_course_flow}}', [
            'id'         => $this->bigPrimaryKey()->comment('自增ID'),
            'venue_id'   => $this->bigInteger()->unsigned()->comment('场馆ID'),
            'coach_id'   => $this->bigInteger()->unsigned()->comment('教练ID'),
            'class_date' => $this->date()->comment('上课日期'),
            'start'      => $this->integer()->comment('开始上课时间'),
            'end'        => $this->integer()->comment('结束时间'),
            'status'     => $this->integer()->comment('状态 1上课2旷课3请假'),

        ], $tableOptions);

        $this->createIndex('index_venue_id', '{{%charge_course_flow}}', 'venue_id');
        $this->createIndex('index_coach_id', '{{%charge_course_flow}}', 'coach_id');

    }
    public function down()
    {
        $this->dropIndex('index_venue_id', '{{%charge_course_flow}}');
        $this->dropIndex('index_coach_id', '{{%charge_course_flow}}');
        $this->dropTable('{{%charge_course_flow}}');

    
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
