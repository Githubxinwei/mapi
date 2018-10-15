<?php

use yii\db\Migration;

class m170618_024449_add_member_cabinet extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_cabinet}}', "member_card_id","bigint COMMENT '会员卡id'");
        $this->createIndex(
            'index_member_card_id',
            '{{%member_cabinet}}',
            'member_card_id'
        );
        $this->addColumn('{{%member_cabinet}}', "rent_type","varchar(255) COMMENT '租柜类型'");
    }

    public function down()
    {
         $this->dropColumn('{{%member_cabinet}}',"member_card_id");
         $this->dropColumn('{{%member_cabinet}}',"rent_type");
    }

}
