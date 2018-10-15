<?php

use yii\db\Migration;

class m170420_112335_update_member_card extends Migration
{
    /**
 * @数据库 - 修改表 - 添加会员卡字段属性
 * @author lihuien <lihuien@itsport.club>
 * @create 2017/4/12
 */
    public function up()
    {
        $this->dropColumn('{{%member_card}}',"payment_time");
        $this->addColumn('{{%member_card}}','payment_time','bigint(20) COMMENT "缴费时间" ');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}',"payment_time");
        $this->addColumn('{{%member_card}}','payment_time','decimal(10,2) COMMENT "缴费时间" ');
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
