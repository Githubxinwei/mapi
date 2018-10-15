<?php

use yii\db\Migration;

class m170520_012130_add_memberKey extends Migration
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
            '{{%member}}',
            'id'
        );
        $this->createIndex(
            'index_counselor_id',
            '{{%member}}',
            'counselor_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id','{{%member}}');
        $this->dropIndex('index_counselor_id','{{%member}}');
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
