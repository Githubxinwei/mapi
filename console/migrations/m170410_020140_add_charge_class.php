<?php

use yii\db\Migration;

class m170410_020140_add_charge_class extends Migration
{
    /**
     * @数据库 - 修改表 - charge添加创建人 ，pic字段
     * @author Hou kaixin<houkaixin@itsport.club>
     * @create 2017/4/5
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%charge_class}}','venue_id','bigint COMMENT "场馆id" ');
        $this->addColumn('{{%charge_class}}','total_amount','decimal COMMENT "总金额" ');
        $this->addColumn('{{%charge_class}}','single_price','decimal COMMENT "单节Pos价" ');

    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}',"venue_id");
        $this->dropColumn('{{%charge_class}}',"total_amount");
        $this->dropColumn('{{%charge_class}}',"single_price");
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
