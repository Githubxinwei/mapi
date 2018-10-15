<?php

use yii\db\Migration;

class m170806_062357_update_gift_record extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_card_category
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/1
     */
    public function up()
    {
        $this->dropIndex('member_id','{{%gift_record}}');
        $this->dropIndex('member_card_id','{{%gift_record}}');
        $this->dropIndex('service_pay_id','{{%gift_record}}');
        $this->alterColumn('{{%gift_record}}', 'num', "int  COMMENT '数量'");
        $this->alterColumn('{{%gift_record}}', 'member_card_id', "bigint  COMMENT '数量'");
    }

    public function down()
    {
        $this->createIndex('member_id','{{%gift_record}}','member_id');
        $this->createIndex('member_card_id','{{%gift_record}}','member_card_id');
        $this->createIndex('service_pay_id','{{%gift_record}}','service_pay_id');
        $this->alterColumn('{{%gift_record}}', 'num', "int  COMMENT '数量'");
        $this->alterColumn('{{%gift_record}}', 'member_card_id', "bigint  COMMENT '数量'");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170806_062357_update_gift_record cannot be reverted.\n";

        return false;
    }
    */
}
