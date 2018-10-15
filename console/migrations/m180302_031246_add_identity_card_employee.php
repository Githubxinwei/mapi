<?php

use yii\db\Migration;

class m180302_031246_add_identity_card_employee extends Migration
{
    public function up()
    {
        $this->addColumn('{{%employee}}','identityCard',"string COMMENT '身份证号'");
    }

    public function down()
    {
        $this->dropColumn('{{%employee}}','identityCard');
    }
}
