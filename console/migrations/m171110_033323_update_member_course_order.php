<?php

use yii\db\Migration;

class m171110_033323_update_member_course_order extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_member_course_order 修改金额数据类型
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/10
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%member_course_order}}','money_amount',"decimal(10,2)	  COMMENT '总金额'");
    }

    public function down()
    {
        $this->alterColumn('{{%member_course_order}}','money_amount',"decimal(10,2)	  COMMENT '总金额'");
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
