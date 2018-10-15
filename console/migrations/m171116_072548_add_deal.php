<?php

use yii\db\Migration;

class m171116_072548_add_deal extends Migration
{
    public function up()
    {
        $this->addColumn('{{%deal}}','type',"tinyint(4) COMMENT '1卡种类,2私课类'");
    }

    public function down()
    {
        $this->dropColumn('{{%deal}}','type');
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
