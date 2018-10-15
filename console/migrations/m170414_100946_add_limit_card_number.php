<?php

use yii\db\Migration;

class m170414_100946_add_limit_card_number extends Migration
{
    /**
     * @数据库 - 通店和发行量表 -  添加字段
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function up()
    {
        $this->addColumn('{{%limit_card_number}}', 'sell_start_time', "bigint(20) UNSIGNED  COMMENT '售卖开始时间'");
        $this->addColumn('{{%limit_card_number}}', 'sell_end_time', "bigint(20) UNSIGNED COMMENT '售卖结束时间'");
    }
    /**
     * @数据库 - 通店和发行量表 -  回滚字段
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function down()
    {
        $this->dropColumn('{{%limit_card_number}}', 'sell_start_time');
        $this->dropColumn('{{%limit_card_number}}', 'sell_end_time');
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
