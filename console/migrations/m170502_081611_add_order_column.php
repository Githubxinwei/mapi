<?php

use yii\db\Migration;

class m170502_081611_add_order_column extends Migration
{
    /**
     * @数据库 - 订单表添加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'card_name', 'string(200) COMMENT "卡名称"');
        $this->addColumn('{{%order}}', 'sell_people_name', 'string(200) COMMENT "售卖人姓名"');
        $this->addColumn('{{%order}}', 'payee_name', 'string(200) COMMENT "收款人姓名"');
        $this->addColumn('{{%order}}', 'member_name', 'string(200) COMMENT "购买人姓名"');

    }
    /**
     * @数据库 - 订单表 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%order}}', 'card_name');
        $this->dropColumn('{{%order}}', 'sell_people_name');
        $this->dropColumn('{{%order}}', 'payee_name');
        $this->dropColumn('{{%order}}', 'member_name');
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
