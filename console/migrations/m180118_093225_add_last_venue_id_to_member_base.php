<?php

use yii\db\Migration;

class m180118_093225_add_last_venue_id_to_member_base extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_base}}','last_venue_id',"tinyint COMMENT '上次登录场馆id'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_base}}','last_venue_id');
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
