<?php

use yii\db\Migration;

class m170504_080820_add_cabinet extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_cabinet增加字段
     * @author Houkaixin <Houkaixin @itsport.club>
     * @create 2017/5/4
     */
    public function up()
    {
        $this->addColumn('{{%cabinet}}', 'dayRentPrice', 'decimal(10,2) COMMENT "薪资"');
        $this->addColumn('{{%cabinet}}', 'monthRentPrice', 'decimal(10,2) COMMENT "薪资"');
        $this->addColumn('{{%cabinet}}', 'yearRentPrice', 'decimal(10,2) COMMENT "薪资"');
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}', 'dayRentPrice');
        $this->dropColumn('{{%cabinet}}', 'monthRentPrice');
        $this->dropColumn('{{%cabinet}}', 'yearRentPrice');
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
