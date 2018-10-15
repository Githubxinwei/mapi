<?php

use yii\db\Migration;

class m170920_075220_add_card_category extends Migration
{
    public function up()
    {
        $this->addColumn('{{%card_category}}','bring',"smallInt COMMENT '带人卡设置，0代表不待人'");
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}','bring');
    }
}
