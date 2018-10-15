<?php

use yii\db\Migration;

class m180104_022021_add_user_active extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_activity_statistics}}', 'member_id', "bigint COMMENT '会员id'");
    }

    public function down()
    {
        $this->dropColumn('{{%user_activity_statistics}}','member_id');
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
