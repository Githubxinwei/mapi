<?php

use yii\db\Migration;

class m170520_030753_add_member_card extends Migration
{
    /**
     * @数据库 - 会员卡表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/20
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%member_card}}',
            'id'
        );
        $this->createIndex(
            'index_deal_id',
            '{{%member_card}}',
            'deal_id'
        );
    }

    public function down()
    {
        $this->dropIndex('index_id','{{%member_card}}');
        $this->dropIndex('index_deal_id','{{%member_card}}');
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
