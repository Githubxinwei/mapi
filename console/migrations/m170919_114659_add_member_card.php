<?php

use yii\db\Migration;

class m170919_114659_add_member_card extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_card}}','last_freeze_time',"bigint COMMENT '最后一次冻结时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','last_freeze_time');
    }
}
