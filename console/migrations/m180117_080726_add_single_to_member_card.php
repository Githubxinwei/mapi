<?php

use yii\db\Migration;

class m180117_080726_add_single_to_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','single',"float COMMENT '单数'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','single');
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
