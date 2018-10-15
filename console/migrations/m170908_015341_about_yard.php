<?php

use yii\db\Migration;

class m170908_015341_about_yard extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="会员场地预约记录" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%about_yard}}', [
            'id'                       => $this->bigPrimaryKey()->comment('自增ID'),
            'yard_id'                 => $this->bigInteger()->unsigned()->comment('场地id'),
            'member_id'               => $this->bigInteger()->unsigned()->comment('会员id'),
            "member_card_id"         => $this->bigInteger()->unsigned()->comment('会员卡id'),
            'about_interval_section'=> $this->string()->comment('预约区间段'),
            "aboutDate"               => $this->string()->comment('预约日期'),
            "cancel_about_time"      =>  $this->bigInteger()->unsigned()->comment('取消预约日期'),
            "status"                  => $this->smallInteger(6)->unsigned()->comment('1:未开始 2:已开始 3:已结束 4:旷课(没去)5:取消预约'),
            "create_at"               => $this->bigInteger()->unsigned()->comment('创建时间(预约时间)'),
        ], $tableOptions);
        $this->createIndex(
            'index_yard_id',
            '{{%about_yard}}',
            'yard_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%about_yard}}',
            'member_id'
        );
        $this->createIndex(
            'index_member_card_id',
            '{{%about_yard}}',
            'member_card_id'
        );
    }

    public function down()
    {
        $this->dropTable("{{%about_yard}}");
    }
}
