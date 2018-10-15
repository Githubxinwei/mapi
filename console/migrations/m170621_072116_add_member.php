<?php

use yii\db\Migration;

class m170621_072116_add_member extends Migration
{
    /**
     * @数据库 - 会员课程订单表 - 增加字段
     * @author houkaixin  <huangpengju@itsports.club>
     * @create 2017/6/21
     */
    public function up()
    {
        $this->addColumn('{{%member}}','fixPhone',"varchar(200) COMMENT '固定电话'");
    }

    public function down()
    {
        $this->dropColumn('{{%member}}','fixPhone');
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
