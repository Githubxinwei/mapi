<?php

use yii\db\Migration;

class m180118_070802_add_charge_class_number extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%charge_class_number}}','attend_class_num',"int COMMENT '剩余节数'");
    }

    public function down()
    {
        $this->alterColumn('{{%charge_class_number}}','attend_class_num',"int(11) COMMENT '已上节数'");
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
