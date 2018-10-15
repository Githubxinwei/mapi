<?php

use yii\db\Migration;

class m171212_093703_add_card_category extends Migration
{
    public function up()
    {
        $this->addColumn('{{%card_category}}','pic',"text COMMENT '图片'");
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}','pic');
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
