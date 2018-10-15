<?php

use yii\db\Migration;

class m170523_025229_add_member_card extends Migration
{
    /**
     * @数据库 - 添加字段 - cloud_member_card(修改会员卡表)
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/5/23
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'duration',"int COMMENT '有效期'");
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'duration');
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
