<?php

use yii\db\Migration;

class m170422_085009_add_service_rule extends Migration
{
    /**
     * @数据库 - 服务收费规则表 -  添加字段
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/4/22
     */
    public function up()
    {
        $this->addColumn('{{%service_rule}}', 'rule', "varchar(200) COMMENT '规则'");
        $this->addColumn('{{%service_rule}}', 'unit_price', "decimal COMMENT '单价'");
        $this->addColumn('{{%service_rule}}', 'create_at', "bigint(20) COMMENT '添加时间'");
    }

    public function down()
    {
        $this->dropColumn('{{%service_rule}}',"rule");
        $this->dropColumn('{{%service_rule}}',"unit_price");
        $this->dropColumn('{{%service_rule}}',"create_at");
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
