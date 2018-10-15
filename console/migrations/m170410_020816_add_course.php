<?php

use yii\db\Migration;

class m170410_020816_add_course extends Migration
{
    public function up()
    {
        $this->addColumn('{{%course}}','class_type','tinyint DEFAULT 1  COMMENT "1私教 2团教" ');
    }

    public function down()
    {
        $this->dropColumn('{{%course}}',"class_type");
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
