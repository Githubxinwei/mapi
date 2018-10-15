<?php

use yii\db\Migration;

class m170524_013429_add_transfer_record extends Migration
{
    /**
     * @数据库 - 添加字段 - 转让记录表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/24
     */
    public function up()
    {
        $this->addColumn('{{%transfer_record}}', 'transfer_price',"decimal COMMENT '转让金额'");
    }

    public function down()
    {
        $this->dropColumn('{{%transfer_record}}', 'transfer_price');
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
