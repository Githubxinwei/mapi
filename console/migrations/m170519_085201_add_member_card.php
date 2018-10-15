<?php

use yii\db\Migration;

class m170519_085201_add_member_card extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_member_card 会员卡表
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/19
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'leave_days_times', 'json COMMENT "每次天数,次数[天，次]"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'leave_days_times');
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
