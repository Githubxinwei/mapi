<?php

use yii\db\Migration;

class m170518_065003_add_member_card extends Migration
{
    /**
     * @数据库 - 会员卡表 - 添加字段
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/5/18
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}','employee_id','bigint(20) COMMENT "员工ID(销售)"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}','employee_id');
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
