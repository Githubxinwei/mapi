<?php

use yii\db\Migration;

/**
 * Class m180312_082517_addMemberAccount
 */
class m180312_082517_addMemberAccount extends Migration
{
    public function up()
    {
        $this->addColumn('{{%member_account}}','count',"tinyint DEFAULT 0 COMMENT '非法登录次数最大三次'");
        $this->addColumn('{{%member_account}}','deviceNumber',"string DEFAULT '' COMMENT '设备识别号'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_account}}','count');
        $this->dropColumn('{{%member_account}}','deviceNumber');

    }
}
