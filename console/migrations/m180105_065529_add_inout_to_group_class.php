<?php

use yii\db\Migration;

/**
 * Class m180105_065529_add_inout_to_group_class
 */
class m180105_065529_add_inout_to_group_class extends Migration
{
    public function up()
    {
        $this->addColumn('{{%group_class}}', 'in_time', "BIGINT(20) NOT NULL DEFAULT 0 COMMENT '上课打卡'");
        $this->addColumn('{{%group_class}}', 'out_time', "BIGINT(20) NOT NULL DEFAULT 0 COMMENT '下课打卡'");
    }

    public function down()
    {
        $this->dropColumn('{{%group_class}}', 'in_time');
        $this->dropColumn('{{%group_class}}', 'out_time');
    }
}
