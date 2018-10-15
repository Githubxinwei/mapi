<?php

use yii\db\Migration;

class m170424_083855_update_organizationOne extends Migration
{
    /**
     * @数据库 - 组织架构修改字段 - params
     * @author 侯凯新 <houkaixin@itsport.club>
     * @create 2017/4/24
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%organization}}', 'params');
        $this->addColumn('{{%organization}}','params',"json  COMMENT '参数 自定义属性 json([免费课量=>10,基础课量=>10])' ");
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}', 'params');
        $this->addColumn('{{%organization}}','params',"json NOT NULL COMMENT '参数 自定义属性 json([免费课量=>10,基础课量=>10])' ");
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
