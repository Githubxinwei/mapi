<?php

use yii\db\Migration;

class m170504_010322_add_about_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%about_class}}', 'member_id', 'bigint(20) COMMENT "会员ID"');
    }

    public function down()
    {
        $this->dropColumn('{{%about_class}}', 'member_id');
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
