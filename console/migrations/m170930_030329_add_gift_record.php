<?php

use yii\db\Migration;

class m170930_030329_add_gift_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%gift_record}}','class_type',"varchar(200) COMMENT '私课类型，hs,pt,birth'");
    }

    public function down()
    {
        $this->dropColumn('{{%gift_record}}','class_type');
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
