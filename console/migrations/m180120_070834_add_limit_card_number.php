<?php

use yii\db\Migration;

class m180120_070834_add_limit_card_number extends Migration
{
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}','about_limit',"smallint default 1 COMMENT '团课预约设置-1不受限制1受'");
    }

    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}','about_limit');
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
