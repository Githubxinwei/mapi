<?php

use yii\db\Migration;

class m171212_054711_add_signature_to_admin extends Migration
{
    public function up()
    {
        $this->addColumn('{{%employee}}','signature',"text COMMENT '签名'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}','signature');
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
