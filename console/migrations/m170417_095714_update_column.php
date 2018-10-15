<?php

use yii\db\Migration;

class m170417_095714_update_column extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_limit_card_number和cloud_card_category表 修改字段
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/17
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%limit_card_number}}', 'times', "int(11)  COMMENT '通店次数'");
        $this->alterColumn('{{%card_category}}', 'original_price', "decimal(10,2)  COMMENT '一口原价'");
        $this->alterColumn('{{%card_category}}', 'sell_price', "decimal(10,2)  COMMENT '一口售价'");
        $this->alterColumn('{{%card_category}}', 'max_price', "decimal(10,2)  COMMENT '最高价'");
        $this->alterColumn('{{%card_category}}', 'min_price', "decimal(10,2)  COMMENT '最低价'");
        $this->alterColumn('{{%card_category}}', 'transfer_price', "decimal(10,2)  COMMENT '转让金额'");
        $this->alterColumn('{{%card_category}}', 'transfer_price', "decimal(10,2)  COMMENT '转让金额'");
    }
    /**
     * 数据库 - 回滚表 - cloud_limit_card_number 和cloud_card_category表 字段
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/17
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('{{%limit_card_number}}', 'times', "	int(11) UNSIGNED  COMMENT '进场次数 '");
        $this->alterColumn('{{%card_category}}', 'original_price', "decimal(10,0)  COMMENT '一口原价'");
        $this->alterColumn('{{%card_category}}', 'sell_price', "decimal(10,0)  COMMENT '一口售价'");
        $this->alterColumn('{{%card_category}}', 'max_price', "decimal(10,0)  COMMENT '最高价'");
        $this->alterColumn('{{%card_category}}', 'min_price', "decimal(10,0)  COMMENT '最低价'");
        $this->alterColumn('{{%card_category}}', 'transfer_price', "int(11)  COMMENT '转让金额'");

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
