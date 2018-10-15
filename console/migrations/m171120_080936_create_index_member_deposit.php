<?php

use yii\db\Migration;

class m171120_080936_create_index_member_deposit extends Migration
{
    public function up()
    {
        $this->createIndex('index_member_id', '{{%member_deposit}}', 'member_id');
    }

    public function down()
    {
        $this->dropIndex('index_member_id', '{{%member_deposit}}');
    }
}
