<?php

use yii\db\Migration;

class m180131_080749_add_gift_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%gift_record}}','type',"smallint default 1 COMMENT '1未撤销2已撤销'");
    }

    public function down()
    {
        $this->dropColumn('{{%gift_record}}','type');
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
