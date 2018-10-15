<?php

use yii\db\Migration;

class m170522_074938_add_consumption_history extends Migration
{
    /**
     * @数据库 - 消费记录表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%consumption_history}}',
            'id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%consumption_history}}');
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
