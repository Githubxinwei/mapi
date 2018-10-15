<?php

use yii\db\Migration;

class m170520_014307_add_member_detailsKey extends Migration
{

    /**
     * @数据库 - 会员详细信息表 -  添加外键约束
     * @author Huang hua <huanghua@itsport.club>
     * @create 2017/5/20
     */
    public function up()
    {
        $this->createIndex(
            'index_id',
            '{{%member_details}}',
            'id'
        );
        $this->createIndex(
            'index_recommend_member_id',
            '{{%member_details}}',
            'recommend_member_id'
        );
    }

    public function down()
    {
//        $this->dropIndex('index_id','{{%member_details}}');
//        $this->dropIndex('index_recommend_member_id','{{%member_details}}');
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
