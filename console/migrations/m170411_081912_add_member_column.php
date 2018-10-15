<?php

use yii\db\Migration;

class m170411_081912_add_member_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member}}', 'counselor_id', "bigint(20) UNSIGNED COMMENT '顾问ID'");
    }

    public function down()
    {
        $this->dropColumn('{{%member}}', 'counselor_id');
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
