<?php

use yii\db\Migration;

class m171127_063750_add_entry_record extends Migration
{
    public function up()
    {
        $this->addColumn('{{%entry_record}}','entry_way',"tinyint(4) default 1 COMMENT '进馆方式1.前台验卡 2闸机刷卡'");
    }

    public function down()
    {
        $this->dropColumn('{{%entry_record}}','entry_way');
    }
}
