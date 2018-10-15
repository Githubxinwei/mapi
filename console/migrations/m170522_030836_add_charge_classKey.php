<?php

use yii\db\Migration;

class m170522_030836_add_charge_classKey extends Migration
{
    /**
     * @数据库 - 收费课程表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_course_id',
            '{{%charge_class}}',
            'course_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%charge_class}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_course_id', '{{%charge_class}}');
        $this->dropIndex('index_venue_id', '{{%charge_class}}');
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
