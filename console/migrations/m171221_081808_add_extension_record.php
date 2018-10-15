<?php

use yii\db\Migration;

class m171221_081808_add_extension_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%extension_record}}','create_id',"bigint COMMENT '创建人Id'");
    }

    public function down()
    {
        $this->dropColumn('{{%extension_record}}','create_id');
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
