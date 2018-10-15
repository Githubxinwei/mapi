<?php

use yii\db\Migration;

class m170706_011838_add_auth extends Migration
{
    /**
     * @数据库 - 权限表 - 公司ID、场馆ID
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/5
     */
    public function up()
    {
        $this->addColumn('{{%auth}}','company_id',"json COMMENT '公司ID'");
        $this->addColumn('{{%auth}}','venue_id',"json COMMENT '场馆ID'");
        $this->addColumn('{{%auth}}','sync_role_id',"bigint COMMENT '同步角色ID'");
    }

    public function down()
    {
        $this->dropColumn('{{%auth}}','company_id');
        $this->dropColumn('{{%auth}}','venue_id');
        $this->dropColumn('{{%auth}}','sync_role_id');
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
