<?php

use yii\db\Migration;

class m170603_070852_add_leave_record extends Migration
{
    /**
     * @数据库 - 添加字段
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/3
     */
    public function up()
    {
        $this->addColumn('{{%leave_record}}','member_card_id',"bigint(20) COMMENT '会员卡id'");
        $this->createIndex(
            'index_member_card_id',
            '{{%leave_record}}',
            'member_card_id'
        );
    }

    public function down()
    {
        $this->dropColumn('{{%leave_record}}','member_card_id');
    }

}
