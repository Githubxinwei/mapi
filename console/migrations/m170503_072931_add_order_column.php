<?php

use yii\db\Migration;

class m170503_072931_add_order_column extends Migration
{
    /**
     * @数据库 - 订单表 添加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/3
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'pay_people_name', 'string(200) COMMENT "付款人姓名"');
        $this->alterColumn('{{%order}}', 'sell_people_id', "bigint(20)  UNSIGNED COMMENT '售卖人id'");

    }
    /**
     * @数据库 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/3
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%order}}', 'pay_people_name');
        $this->alterColumn('{{%order}}', 'sell_people_id', " bigint(20) UNSIGNED NOT NULL COMMENT '售卖人id '");

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
