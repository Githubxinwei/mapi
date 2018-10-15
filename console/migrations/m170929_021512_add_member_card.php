<?php

use yii\db\Migration;

class m170929_021512_add_member_card extends Migration
{
    public function up()
    {
       $this->addColumn('{{%member_card}}','recent_freeze_reason',"smallInt default 2 COMMENT '1:团课爽约被冻结 2:其它原因被冻结'");
    }

    public function down()
    {
       $this->dropColumn('{{%member_card}}','recent_freeze_reason');
    }

  
}
