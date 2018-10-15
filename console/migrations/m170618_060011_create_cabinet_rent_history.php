<?php

use yii\db\Migration;

class m170618_060011_create_cabinet_rent_history extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_class_record  会员柜子历史租用记录表
         * @author houkaixin <houkaixin@itsports.club>
         * @create 2017/6/18
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '柜子租用历史记录表'  CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }

        $this->createTable('{{%member_cabinet_rent_history}}', [
            'id'             => $this->bigPrimaryKey()->comment('自增ID'),
            'member_id'     => $this->bigInteger()->unsigned()->comment('会员id'),
            'price'          => $this->decimal(10,2)->comment('租金'),
            'start_rent'    => $this->bigInteger()->unsigned()->comment('起租日'),
            'end_rent'      => $this->bigInteger()->unsigned()->comment('到期日'),
            'back_rent'     => $this->bigInteger()->unsigned()->comment('退租日'),
            'create_at'    => $this->bigInteger()->unsigned()->comment('创建日期'),
            'cabinet_id'   => $this->bigInteger()->unsigned()->comment('柜子id'),
            "remark"       => $this->text()->unsigned()->comment('备注'),
            "member_card_id"=>$this->bigInteger()->unsigned()->comment('会员卡id'),
            'rent_type'   => $this->string(255)->comment('柜子租用类型'),
        ], $tableOptions);
        $this->createIndex('index_member_id','{{%member_cabinet_rent_history}}','member_id');
        $this->createIndex('cabinet_id','{{%member_cabinet_rent_history}}','cabinet_id');
        $this->createIndex('member_card_id','{{%member_cabinet_rent_history}}','member_card_id');
    }


    public function down()
    {
        $this->dropTable('{{%member_cabinet_rent_history}}');
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
