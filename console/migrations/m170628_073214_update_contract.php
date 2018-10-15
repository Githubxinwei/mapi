<?php

use yii\db\Migration;

class m170628_073214_update_contract extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%deal}}', 'intro');
        $this->addColumn('{{%deal}}', 'intro',"LONGTEXT COMMENT '描述'");
    }

    public function down()
    {
        $this->dropColumn('{{%deal}}', 'intro');
        $this->addColumn('{{%deal}}', 'intro',"LONGTEXT COMMENT '描述'");
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
