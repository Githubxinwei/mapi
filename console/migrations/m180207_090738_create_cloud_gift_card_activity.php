<?php

use yii\db\Migration;

class m180207_090738_create_cloud_gift_card_activity extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '新增赠送会员卡活动表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%gift_card_activity}}', [
            'id'                => $this->bigPrimaryKey()->comment('自增ID'),
            'active_name'       => $this->string(200)->notNull()->comment('赠送名称'),
            'gift_card_num'     => $this->bigInteger()->unsigned()->comment('赠送卡总数量'),
            'active_card_num'   => $this->bigInteger()->unsigned()->comment('开卡数量'),
            'create_time'       => $this->bigInteger()->unsigned()->comment('创建时间'),
            'start_time'        => $this->bigInteger()->unsigned()->comment('活动开始时间(开始开卡时间)'),
            'end_time'          => $this->bigInteger()->unsigned()->comment('活动结束时间(开始结束时间)'),
            'note'              => $this->string(200)->comment('备注'),
            'venue_id'          => $this->bigInteger()->unsigned()->comment('场馆ID'),
            'company_id'        => $this->bigInteger()->unsigned()->comment('公司ID'),
            'operator_id'       => $this->bigInteger()->unsigned()->comment('操作人ID'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%gift_card_activity}}');
    }
}
