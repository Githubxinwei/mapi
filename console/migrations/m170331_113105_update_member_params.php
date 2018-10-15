<?php

use yii\db\Migration;

class m170331_113105_update_member_params extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%member}}', 'params', "json NULL  COMMENT '扩展(json) '");
        $this->alterColumn('{{%card_category}}', 'total_store_times', "int(11) NULL DEFAULT 0  COMMENT '总通店次数：-1不限'");
    }

    public function down()
    {
        $this->alterColumn('{{%member}}', 'params', "json NOT NULL  COMMENT '扩展(json) '");
        $this->alterColumn('{{%card_category}}', 'total_store_times', "int(11) 	UNSIGNED NULL DEFAULT 0  COMMENT '扩展(总通电次数：-1不限) '");
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
