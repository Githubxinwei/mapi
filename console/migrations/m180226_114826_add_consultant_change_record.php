<?php

use yii\db\Migration;

class m180226_114826_add_consultant_change_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%consultant_change_record}}','behavior',"smallint COMMENT '行为:1入馆2办卡3修改4续费5升级'");
    }

    public function down()
    {
        $this->dropColumn('{{%consultant_change_record}}','behavior');
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
