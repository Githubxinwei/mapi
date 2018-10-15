<?php

use yii\db\Migration;

class m171020_025209_add_extension_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%extension_record}}','member_id',"bigint COMMENT '会员id'");
    }

    public function down()
    {
        $this->dropColumn('{{%extension_record}}','member_id');
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
