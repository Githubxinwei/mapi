<?php

use yii\db\Migration;

class m180111_020524_alter_member_cabinet extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%member_cabinet}}','price',"decimal(10,2) COMMENT '金额'");
    }

    public function down()
    {
        $this->alterColumn('{{%member_cabinet}}','price',"decimal(10,2) COMMENT '金额'");
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
