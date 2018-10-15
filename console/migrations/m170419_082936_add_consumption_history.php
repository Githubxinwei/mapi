<?php

use yii\db\Migration;

class m170419_082936_add_consumption_history extends Migration
{
    /**
     * @数据库 - 消费记录表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/18
     */
    public function up()
    {
        $this->addColumn('{{%consumption_history}}', 'type', "int(11) UNSIGNED COMMENT '消费方式(1现金2次卡3充值卡)'");
        $this->addColumn('{{%consumption_history}}', 'consumption_date', "bigint(20) UNSIGNED COMMENT '消费日期'");
        $this->addColumn('{{%consumption_history}}', 'consumption_amount', "decimal UNSIGNED COMMENT '消费金额'");

    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}', 'type');
        $this->dropColumn('{{%consumption_history}}', 'consumption_date');
        $this->dropColumn('{{%consumption_history}}', 'consumption_amount');


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
