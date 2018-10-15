<?php

use yii\db\Migration;

class m170612_063251_add_memcabibet extends Migration
{
    /**
     * @数据库 -  会员柜子表 - 更改柜子表（调柜变更id））
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/12
     */
    public function up()
    {
        $this->addColumn('{{%member_cabinet}}', 'change_cabinet_remark', "json COMMENT '变更柜子id记录'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_cabinet}}', 'change_cabinet_remark');
    }
}
