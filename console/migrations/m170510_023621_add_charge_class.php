<?php

use yii\db\Migration;

class m170510_023621_add_charge_class extends Migration
{
    /**
     * @数据库 - 添加字段 - cloud_charge_class 私课表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/10
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%charge_class}}', 'transfer_num', 'smallint(6) COMMENT "转让次数"');
        $this->addColumn('{{%charge_class}}', 'transfer_price', 'decimal(10,2) COMMENT "转让金额"');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}', 'transfer_num');
        $this->dropColumn('{{%charge_class}}', 'transfer_price');
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
