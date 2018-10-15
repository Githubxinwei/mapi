<?php

use yii\db\Migration;

class m171016_025638_add_gift_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%gift_record}}','note',"varchar(200) COMMENT '备注'");
    }

    public function down()
    {
        $this->dropColumn('{{%gift_record}}','note');
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
