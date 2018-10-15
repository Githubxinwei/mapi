<?php

use yii\db\Migration;

/**
 * Class m180106_010236_add_in_time_to_about_class
 */
class m180106_010236_add_in_time_to_about_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%about_class}}', 'in_time', "BIGINT(20) NOT NULL DEFAULT 0 COMMENT '手环上课打卡时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%about_class}}', 'in_time');
    }
}
