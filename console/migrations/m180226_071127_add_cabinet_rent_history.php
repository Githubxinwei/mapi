<?php

use yii\db\Migration;

class m180226_071127_add_cabinet_rent_history extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_cabinet_rent_history}}', 'give_month', "int(11)  COMMENT '赠送月数'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_cabinet_rent_history}}', 'give_month');
    }

}
