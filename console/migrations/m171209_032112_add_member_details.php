<?php

use yii\db\Migration;

class m171209_032112_add_member_details extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_details}}','document_type',"smallint(4) COMMENT '证件类型:1身份证2居住证3签证4护照5户口本6军人证'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}','document_type');
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
