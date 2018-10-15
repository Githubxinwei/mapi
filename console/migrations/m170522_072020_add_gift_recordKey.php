<?php

use yii\db\Migration;

class m170522_072020_add_gift_recordKey extends Migration
{
    /**
     * @数据库 - 赠品记录表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%gift_record}}',
            'id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%gift_record}}');
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
