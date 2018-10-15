<?php

use yii\db\Migration;

/**
 * Class m171229_071004_add_private_id_to_member
 */
class m171229_071004_add_private_id_to_member extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member}}', 'private_id', "INT(10) DEFAULT 0 COMMENT '用于会员购卡未购课时分配私教以促进销售'");
    }

    public function down()
    {
        $this->dropColumn('{{%member}}', 'private_id');
    }
}
