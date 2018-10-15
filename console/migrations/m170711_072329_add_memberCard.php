<?php

use yii\db\Migration;

class m170711_072329_add_memberCard extends Migration
{
    /**
     * @数据库 - 旷课次数- 新增会员卡旷课次数字段
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/7/11
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}','absentTimes',"int COMMENT '旷课次数'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','absentTimes');
    }
}
