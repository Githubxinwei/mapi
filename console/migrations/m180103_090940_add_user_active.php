<?php

use yii\db\Migration;

class m180103_090940_add_user_active extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_activity_statistics}}', 'device_number', "string(255) COMMENT '设备编号'");
    }

    public function down()
    {
        $this->dropColumn('{{%user_activity_statistics}}', 'device_number');
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
