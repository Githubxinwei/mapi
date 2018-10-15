<?php

use yii\db\Migration;

class m170905_024634_add_limit_card_number_week_times extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_module表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/28
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}', 'week_times', 'int(11) COMMENT "周限制次数"');
        $this->addColumn('{{%venue_limit_times}}', 'week_times', 'int(11) COMMENT "周限制次数"');
    }

    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}', 'week_times');
        $this->dropColumn('{{%venue_limit_times}}', 'week_times');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170905_024634_add_limit_card_number_week_times cannot be reverted.\n";

        return false;
    }
    */
}
