<?php

use yii\db\Migration;

class m171218_081838_add_scan_code extends Migration
{
    public function up()
    {
        $this->addColumn('{{%scan_code_record}}','identify',"tinyint(5) default 1 COMMENT '1:会员 2:员工'");
    }

    public function down()
    {
        $this->dropColumn('{{%scan_code_record}}','identify');
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
