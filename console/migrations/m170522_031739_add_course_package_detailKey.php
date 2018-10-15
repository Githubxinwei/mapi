<?php

use yii\db\Migration;

class m170522_031739_add_course_package_detailKey extends Migration
{
    /**
     * @数据库 - 课程套餐详情表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%course_package_detail}}',
            'id'
        );

    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%course_package_detail}}');
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
