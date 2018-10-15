<?php

use yii\db\Migration;

class m171103_020404_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','ordinary_renewal',"decimal COMMENT '普通续费'");
        $this->addColumn('{{%member_card}}','validity_renewal',"json COMMENT '有效期续费'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','ordinary_renewal');
        $this->dropColumn('{{%member_card}}','validity_renewal');
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
