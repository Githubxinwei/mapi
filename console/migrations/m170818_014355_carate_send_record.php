<?php

use yii\db\Migration;

class m170818_014355_carate_send_record extends Migration
{
    public function up()
    {
        /**
         * @数据库 - 创建表 - cloud_image_management 图片管理表
         * @author huanghua<huanghua@itsports.club>
         * @create 2017/8/11
         * @inheritdoc
         */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '会员卡赠送记录表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%send_record}}', [
            'id'                 => $this->bigPrimaryKey()->comment('自增Id'),
            'member_id'          => $this->bigInteger()->unsigned()->comment('赠送会员ID'),
            'cover_member_id'    => $this->bigInteger()->unsigned()->comment('被赠送赠送会员ID'),
            'member_card_id'     => $this->bigInteger()->unsigned()->comment('被赠送会员卡ID'),
            'note'               => $this->string(255)->unsigned()->comment('备注'),
            'send_time'          => $this->bigInteger()->comment('赠送时间'),
            'created_at'         => $this->bigInteger()->comment('创建时间'),
        ], $tableOptions);
        $this->createIndex(
            'index_member_card_id',
            '{{%send_record}}',
            'member_card_id'
        );
        $this->createIndex(
            'index_cover_member_id',
            '{{%send_record}}',
            'cover_member_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%send_record}}',
            'member_id'
        );
    }
    public function down()
    {
        $this->dropIndex('index_member_card_id','{{%send_record}}');
        $this->dropIndex('index_cover_member_id','{{%send_record}}');
        $this->dropIndex('index_member_id','{{%send_record}}');
        $this->dropTable('{{%send_record}}');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170818_014355_carate_send_record cannot be reverted.\n";

        return false;
    }
    */
}
