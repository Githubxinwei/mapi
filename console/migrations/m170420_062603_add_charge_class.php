<?php

use yii\db\Migration;

class m170420_062603_add_charge_class extends Migration
{
    /**
     * @数据库 - 修改表 - charge_class
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/4/20
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%charge_class}}','valid_time','int(11) COMMENT "有效期" ');
        $this->addColumn('{{%charge_class}}','activated_time','int(11) COMMENT "激活期限" ');
        $this->addColumn('{{%charge_class}}','total_sale_num','int(11) COMMENT "售卖总量" ');
        $this->addColumn('{{%charge_class}}','sale_start_time','bigint(20) COMMENT "售卖开始日期" ');
        $this->addColumn('{{%charge_class}}','sale_end_time','bigint(20) COMMENT "售卖结束日期" ');
        $this->addColumn('{{%charge_class}}','total_pos_price','decimal COMMENT "总POS价" ');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}',"valid_time");
        $this->dropColumn('{{%charge_class}}',"activated_time");
        $this->dropColumn('{{%charge_class}}',"total_sale_num");
        $this->dropColumn('{{%charge_class}}',"sale_start_time");
        $this->dropColumn('{{%charge_class}}',"sale_end_time");
        $this->dropColumn('{{%charge_class}}',"total_pos_price");
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
