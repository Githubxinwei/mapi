<?php

use yii\db\Migration;

class m170504_124310_add_consumption_historyTwo extends Migration
{
    /**
     * @数据库 - 消费记录表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/4
     */
    public function up()
    {

        $this->addColumn('{{%consumption_history}}', 'consumption_time', "bigint(20) UNSIGNED COMMENT '消费时间'");
        $this->addColumn('{{%consumption_history}}', 'consumption_times', "bigint(20) UNSIGNED COMMENT '消费次数'");

    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}', 'consumption_time');
        $this->dropColumn('{{%consumption_history}}', 'consumption_times');
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
