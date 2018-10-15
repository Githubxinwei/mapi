<?php

use yii\db\Migration;

class m170520_031924_add_employeeKey extends Migration
{
    /**
     * @数据库 - 员工表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/20
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%employee}}',
            'id'
        );
        $this->createIndex(
            'index_admin_user_id',
            '{{%employee}}',
            'admin_user_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id','{{%employee}}');
        $this->dropIndex('index_admin_user_id','{{%employee}}');
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
