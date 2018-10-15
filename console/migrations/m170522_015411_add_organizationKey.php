<?php

use yii\db\Migration;

class m170522_015411_add_organizationKey extends Migration
{
    /**
     * @数据库 - 组织架构表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%organization}}',
            'id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%organization}}',
            'create_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%organization}}');
        $this->dropIndex('index_create_id', '{{%organization}}');
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
