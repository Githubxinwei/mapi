<?php

use yii\db\Migration;

class m170520_062555_add_member_cardKey extends Migration
{
    public function up()
    {
        $this->createIndex(
            'index_card_category_id',
            '{{%member_card}}',
            'card_category_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%member_card}}',
            'member_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_card_category_id', '{{%member_card}}');
        $this->dropIndex('index_member_id', '{{%member_card}}');
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
