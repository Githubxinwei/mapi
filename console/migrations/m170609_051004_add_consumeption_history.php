<?php

use yii\db\Migration;

class m170609_051004_add_consumeption_history extends Migration
{
    /**
     * @数据库 -  消费历史记录表 - 添加字段（消费金额）
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/12
     */
    public function up()
    {
        $this->dropColumn('{{%consumption_history}}', 'consumption_amount');
        $this->addColumn('{{%consumption_history}}', 'consumption_amount', "decimal(10,2) UNSIGNED COMMENT '消费金额'");
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}', 'consumption_amount');
        $this->addColumn('{{%consumption_history}}', 'consumption_amount', "decimal(10,2) UNSIGNED COMMENT '消费金额'");
    }
}
