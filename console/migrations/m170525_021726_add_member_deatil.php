<?php

use yii\db\Migration;

class m170525_021726_add_member_deatil extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%member_details}}', 'recommend_member_id');
        $this->addColumn('{{%member_details}}', 'recommend_member_id',"bigint(20) COMMENT '推荐人id'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}', 'recommend_member_id');
        $this->addColumn('{{%member_details}}', 'recommend_member_id',"bigint(20) COMMENT '推荐人id'");
    }
}
