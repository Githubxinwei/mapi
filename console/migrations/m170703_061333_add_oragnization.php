<?php

use yii\db\Migration;

class m170703_061333_add_oragnization extends Migration
{
    /**
     * @数据库 - 组织架构表 - 场馆成立时间
     * @author houkaixin  <houkaixin@itsports.club>
     * @create 2017/7/3
     */
    public function up()
    {
        $this->addColumn('{{%organization}}','establish_time',"bigint COMMENT '成立时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}','establish_time');
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
