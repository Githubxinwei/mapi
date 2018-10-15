<?php

use yii\db\Migration;

class m180313_113159_add_create_id_to_cabinet_rent extends Migration
{
    public function up()
    {
        $this->addColumn("{{%member_cabinet_rent_history}}","create_id","int COMMENT '操作人id'");
    }

    public function down()
    {
        $this->dropColumn("{{%member_cabinet_rent_history}}",'create_id');
    }
}
