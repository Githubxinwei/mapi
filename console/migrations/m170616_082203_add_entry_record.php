<?php

use yii\db\Migration;

class m170616_082203_add_entry_record extends Migration
{
    /**
     * @数据库 -  会员进场记录表 - 增加字段
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/16
     */
    public function up()
    {
        $this->addColumn('{{%entry_record}}', "company_id","bigint(20) COMMENT '场馆id'");
    }

    public function down()
    {
        $this->dropColumn('{{%entry_record}}',"company_id");
    }
}
