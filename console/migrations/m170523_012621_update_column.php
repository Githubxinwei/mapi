<?php

use yii\db\Migration;

class m170523_012621_update_column extends Migration
{
    /**
     * @数据库 - 添加、修改字段 - cloud_member 会员表 和 cloud_entry_record 进场表
     * @author 黄鹏举 <houkaixin@itsports.club>
     * @create 2017/5/23
     */
    public function up()
    {
        $this->addColumn('{{%member}}', 'member_type', 'smallint(6) DEFAULT 1 COMMENT "会员类型（1：普通会员 2：潜在会员）"');
        $this->alterColumn('{{%entry_record}}', 'member_card_id',"bigint(20) COMMENT '会员卡id	'");

    }
    /**
     * @数据库 - 回滚字段
     * @author 黄鹏举 <houkaixin@itsports.club>
     * @create 2017/5/23
     */
    public function down()
    {
        $this->dropColumn('{{%member}}', 'member_type');
        $this->alterColumn('{{%entry_record}}', 'member_card_id',"bigint(20) UNSIGNED NOT NULL  COMMENT '会员卡id	'");
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
