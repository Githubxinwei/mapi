<?php

use yii\db\Migration;

class m170803_014017_add_unit_to_goods extends Migration
{
    public function up()
    {
        $this->addColumn('{{%goods}}','unit',"varchar(3) COMMENT '商品单位'");

    }

    public function down()
    {
        $this->dropColumn('{{%goods}}','unit');

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
