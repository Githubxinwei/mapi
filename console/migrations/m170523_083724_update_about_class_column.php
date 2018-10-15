<?php

use yii\db\Migration;

class m170523_083724_update_about_class_column extends Migration
{
    /**
     * @数据库 - 修改字段 -  约课表
     * @author 黄鹏举 <houkaixin@itsports.club>
     * @create 2017/5/23
     */
    public function up()
    {
        $this->alterColumn('{{%about_class}}', 'member_card_id',"bigint(20) COMMENT '会员卡ID'");
    }
    /**
     * @数据库 - 回滚字段 -  约课表
     * @author 黄鹏举 <houkaixin@itsports.club>
     * @create 2017/5/23
     */
    public function down()
    {
        $this->alterColumn('{{%about_class}}', 'member_card_id',"bigint(20) UNSIGNED NOT NULL  COMMENT '会员卡ID'");
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
