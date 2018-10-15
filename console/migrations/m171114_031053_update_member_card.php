<?php

use yii\db\Migration;

class m171114_031053_update_member_card extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_member_card 修改金额数据类型
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/14
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%member_card}}','amount_money',"decimal(10,2)	  COMMENT '卡金额'");
    }

    public function down()
    {
        $this->alterColumn('{{%member_card}}','amount_money',"decimal(10,2)	  COMMENT '卡金额'");
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
