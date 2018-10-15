<?php

use yii\db\Migration;

class m170801_092812_update_charge_class extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_charge_class表 修改字段
     * @author Huang hua <Huanghua@itsport.club>
     * @create 2017/8/1
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%charge_class}}', 'original_price', "decimal(10,2)  COMMENT '原价'");
        $this->alterColumn('{{%charge_class}}', 'sell_price', "decimal(10,2)  COMMENT '售价'");
        $this->alterColumn('{{%charge_class}}', 'total_amount', "decimal(10,2)  COMMENT '总金额'");
        $this->alterColumn('{{%charge_class}}', 'single_price', "decimal(10,2)  COMMENT '单节pos价'");
        $this->alterColumn('{{%charge_class}}', 'total_pos_price', "decimal(10,2)  COMMENT '总pos价'");
    }
    /**
     * 数据库 - 回滚表 - cloud_charge_class表 字段
     * @author Huang hua <Huanghua@itsport.club>
     * @create 2017/8/1
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('{{%charge_class}}', 'original_price', "decimal(10,0)  COMMENT '原价'");
        $this->alterColumn('{{%charge_class}}', 'sell_price', "decimal(10,0)  COMMENT '售价'");
        $this->alterColumn('{{%charge_class}}', 'total_amount', "decimal(10,0)  COMMENT '总金额'");
        $this->alterColumn('{{%charge_class}}', 'single_price', "decimal(10,0)  COMMENT '单节pos价'");
        $this->alterColumn('{{%charge_class}}', 'total_pos_price', "decimal(10,0)  COMMENT '总pos价'");

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
