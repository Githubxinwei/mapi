<?php

use yii\db\Migration;

class m170524_073851_add_member_card extends Migration
{
    /**
     * @数据库 - 添加字段 - 会员卡表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/24
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'surplus',"smallint COMMENT '剩余转让次数'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'surplus');
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
