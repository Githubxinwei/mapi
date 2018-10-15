<?php

use yii\db\Migration;

class m170511_033429_update_card_category_column extends Migration
{
    /**
     * @数据库 - 卡种表 - 添加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'offer_price',"decimal(10,2) COMMENT '优惠价'");
        $this->alterColumn('{{%limit_card_number}}', 'limit', "int(11)   COMMENT '卡限发量（-1代表不限）'");
    }
    /**
     * @数据库 - 卡种表 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'offer_price');
        $this->alterColumn('{{%limit_card_number}}', 'limit', "int(11) UNSIGNED NULL DEFAULT 0 COMMENT '卡限发量'");
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
