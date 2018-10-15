<?php

use yii\db\Migration;

class m170821_022755_add_member_course_order extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_member_course_order表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/21
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_course_order}}', 'course_type', 'smallint COMMENT "课程类型:1收费课,2免费课,3生日课"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_course_order}}', 'course_type');
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
