<?php

use yii\db\Migration;

class m180207_092655_create_cloud_gift_card_list extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '新增赠送会员卡列表数据表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%gift_card_list}}', [
            'id'                        => $this->bigPrimaryKey()->comment('自增ID'),
            'gift_card_activity_id'     => $this->bigInteger()->unsigned()->comment('新增赠送活动表id'),
            'category_type_id'          => $this->bigInteger()->unsigned()->comment('卡种类型id'),
            'card_category_id'          => $this->bigInteger()->unsigned()->comment('卡种id'),
            'card_number'               => $this->string(200)->notNull()->unique()->comment('卡号'),
            'ID_code'                   => $this->string(200)->notNull()->unique()->comment('识别码'),
            'member_id'                 => $this->bigInteger()->unsigned()->comment('会员id'),
            'mobile'                    => $this->string(200)->comment('手机号'),
            'nickname'                  => $this->string(200)->comment('会员昵称'),
            'create_time'               => $this->bigInteger()->unsigned()->comment('创建时间'),
            'update_time'               => $this->bigInteger()->unsigned()->comment('修改时间'),
            'is_bind'                   => $this->bigInteger()->unsigned()->defaultValue(1)->comment('是否绑定(1.未绑定 2.绑定)'),
            'venue_id'                  => $this->bigInteger()->unsigned()->comment('场馆ID'),
            'company_id'                => $this->bigInteger()->unsigned()->comment('公司ID'),
            'operator_id'               => $this->bigInteger()->unsigned()->comment('操作人ID'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%gift_card_list}}');
    }
}
