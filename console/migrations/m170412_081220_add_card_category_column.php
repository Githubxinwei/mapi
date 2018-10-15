<?php

use yii\db\Migration;

class m170412_081220_add_card_category_column extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_card_category 创建充值卡属性字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'recharge_price', "decimal(10,2) COMMENT '充值卡充值金额'");
        $this->addColumn('{{%card_category}}', 'recharge_give_price', "decimal(10,2)  COMMENT '赠送金额'");
        $this->addColumn('{{%card_category}}', 'single_price', "decimal(10,2)  COMMENT '单次消费金额'");
        $this->addColumn('{{%card_category}}', 'recharge_start_time', "varchar(200)  COMMENT '按时段消费开始时间'");
        $this->addColumn('{{%card_category}}', 'recharge_ent_time', "varchar(200) COMMENT '按时段消费结束时间'");
        $this->addColumn('{{%limit_card_number}}', 'status', "int(11) COMMENT '状态1:进 2:卖 3:进卖 4:私教'");
        $this->addColumn('{{%charge_class}}', 'status', "int(11) DEFAULT 1 COMMENT '状态1:正常 2:冻结 3:过期'");
    }
    /**
     * @数据库 - 修改表 - 回滚cloud_card_category 创建充值卡属性字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'recharge_price');
        $this->dropColumn('{{%card_category}}', 'recharge_give_price');
        $this->dropColumn('{{%card_category}}', 'single_price');
        $this->dropColumn('{{%card_category}}', 'recharge_start_time');
        $this->dropColumn('{{%card_category}}', 'recharge_ent_time');
        $this->dropColumn('{{%limit_card_number}}', 'status');
        $this->dropColumn('{{%charge_class}}', 'status');
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
