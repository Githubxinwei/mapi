<?php

use yii\db\Migration;

class m170522_074022_add_entry_recordKey extends Migration
{
    /**
     * @数据库 - 进场记录表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/22
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%entry_record}}',
            'id'
        );
        $this->createIndex(
            'index_member_card_id',
            '{{%entry_record}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_venue_id',
            '{{%entry_record}}',
            'venue_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%entry_record}}',
            'member_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id', '{{%entry_record}}');
        $this->dropIndex('index_member_card_id', '{{%entry_record}}');
        $this->dropIndex('index_venue_id', '{{%entry_record}}');
        $this->dropIndex('index_member_id', '{{%entry_record}}');
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
