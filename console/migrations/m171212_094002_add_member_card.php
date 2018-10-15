<?php

use yii\db\Migration;

class m171212_094002_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','pic',"text COMMENT '图片'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','pic');
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
