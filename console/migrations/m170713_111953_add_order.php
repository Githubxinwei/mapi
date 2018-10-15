<?php

use yii\db\Migration;

class m170713_111953_add_order extends Migration
{
    /**
     * @数据库 - 会员详情表 - 添加字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/7/13
     */
    public function up()
    {
        $this->addColumn('{{%order}}','consumption_type_id',"bigint(20) COMMENT '多态id'");
        $this->addColumn('{{%order}}','consumption_type',"varchar(200) COMMENT '多态类型'");

    }
    /**
     * @数据库 - 会员详情表 - 回滚字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/7/13
     */
    public function down()
    {
        $this->dropColumn('{{%order}}','consumption_type_id');
        $this->dropColumn('{{%order}}','consumption_type');
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
