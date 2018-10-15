<?php

use yii\db\Migration;

class m170522_064336_add_cabinet_typeKey extends Migration
{
    /**
     * @数据库 - 柜子类型表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%cabinet_type}}',
            'id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%cabinet_type}}',
            'venue_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%cabinet_type}}');
        $this->dropIndex('index_venue_id', '{{%cabinet_type}}');
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
