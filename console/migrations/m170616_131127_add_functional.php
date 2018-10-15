<?php

use yii\db\Migration;

class m170616_131127_add_functional extends Migration
{
    public function up()
    {
        $this->addColumn('{{%functional}}', 'update_at', "bigint(20) COMMENT '修改時間'");
    }

    public function down()
    {
        $this->dropColumn('{{%functional}}', 'update_at');
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
