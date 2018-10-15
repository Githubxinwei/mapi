<?php

use yii\db\Migration;

class m170413_033948_add_column extends Migration
{
    /**
     * @数据库 - 修改表 - 回滚cloud_card_category 创建充值卡属性字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'service_pay_ids', "json COMMENT '消费表ID'");
    }
    /**
     * @数据库 - 修改表 - 回滚cloud_card_category 创建充值卡属性字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'service_pay_ids');
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
