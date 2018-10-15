<?php

use yii\db\Migration;

class m171220_063100_update_seat extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%seat}}','seat_number',"varchar(200)	  COMMENT '座位号'");
    }

    public function down()
    {
        $this->alterColumn('{{%seat}}','seat_number',"varchar(200)	  COMMENT '座位号'");
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
