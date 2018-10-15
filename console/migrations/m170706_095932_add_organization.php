<?php

use yii\db\Migration;

class m170706_095932_add_organization extends Migration
{
    /**
     * @数据库 - 组织架构表 - 公司ID、场馆ID
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/6
     */
    public function up()
    {
        $this->addColumn('{{%organization}}','status',"smallint COMMENT '状态：1正常；2停用'");
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}','status');
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
