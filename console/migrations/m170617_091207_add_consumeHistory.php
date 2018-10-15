<?php

use yii\db\Migration;

class m170617_091207_add_consumeHistory extends Migration
{
    public function up()
    {
        $this->addColumn('{{%consumption_history}}', "consume_describe","json COMMENT '消费描述'");
        $this->addColumn('{{%consumption_history}}', "remarks","text COMMENT '备注'");
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}',"consume_describe");
        $this->dropColumn('{{%consumption_history}}',"remarks");
    }

}
