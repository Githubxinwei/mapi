<?php

use yii\db\Migration;

class m170527_081155_create_add_admin extends Migration
{
    public function up()
    {
        $this->addColumn('{{%admin}}','level',"tinyint(4) COMMENT '管理权限级别'");
    }

    public function down()
    {
        $this->dropColumn('{{%admin}}','level');
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
