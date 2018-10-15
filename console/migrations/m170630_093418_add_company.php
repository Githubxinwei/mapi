<?php

use yii\db\Migration;

class m170630_093418_add_company extends Migration
{
    public function up()
    {
        $this->addColumn('{{%organization}}','is_allowed_join','tinyint COMMENT "是否查看(1可以查看2不可以查看)" ');
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}','is_allowed_join');
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
