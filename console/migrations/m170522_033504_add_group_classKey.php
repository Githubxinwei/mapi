<?php

use yii\db\Migration;

class m170522_033504_add_group_classKey extends Migration
{
    /**
     * @数据库 - 团课课程安排表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%group_class}}',
            'id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%group_class}}');
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
