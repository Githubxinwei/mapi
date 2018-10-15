<?php

use yii\db\Migration;

class m170707_035259_add_member_details extends Migration
{
    /**
     * @数据库 - 会员详情表 - 添加昵称字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/7/7
     */
    public function up()
    {
        $this->addColumn('{{%member_details}}','nickname',"varchar(200) COMMENT '会员昵称'");
    }
    /**
     * @数据库 - 会员详情表 - 回滚昵称字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/7/7
     */
    public function down()
    {
        $this->dropColumn('{{%member_details}}','nickname');
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
