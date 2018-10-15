<?php

use yii\db\Migration;

class m171025_022301_add_level_to_venue_limit_times extends Migration
{
    public function up()
    {
        $this->addColumn('{{%venue_limit_times}}','level',"smallInt COMMENT '等级'");
    }

    public function down()
    {
        $this->dropColumn('{{%venue_limit_times}}','level');
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
