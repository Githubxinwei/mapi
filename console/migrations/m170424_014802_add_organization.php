<?php

use yii\db\Migration;

class m170424_014802_add_organization extends Migration
{
    /**
     * @数据库 - 组织架构增加字段 - update_at，create_id,path
     * @author 侯凯新 <houkaixin@itsport.club>
     * @create 2017/4/24
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%organization}}','update_at','bigint COMMENT "更新时间" ');
        $this->addColumn('{{%organization}}', 'create_id', "bigint  COMMENT '创建人id'");
        $this->addColumn('{{%organization}}','path','json COMMENT "路径(json)" ');
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}', 'update_at');
        $this->dropColumn('{{%organization}}', 'create_id');
        $this->dropColumn('{{%organization}}', 'path');
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
