<?php

use yii\db\Migration;

/**
 * Class m180108_065740_add_sn_to_classroom
 */
class m180108_065740_add_sn_to_classroom extends Migration
{
    public function up()
    {
        $this->addColumn('{{%classroom}}', 'sn', "VARCHAR(200) NOT NULL DEFAULT '' COMMENT '设备ID'");
        $this->addColumn('{{%classroom}}', 'readno', "VARCHAR(200) NOT NULL DEFAULT '' COMMENT '读卡器ID'");
    }

    public function down()
    {
        $this->dropColumn('{{%classroom}}', 'sn');
        $this->dropColumn('{{%classroom}}', 'readno');
    }
}
