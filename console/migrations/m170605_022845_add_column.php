<?php

use yii\db\Migration;

class m170605_022845_add_column extends Migration
{
    /**
     * @数据库 - 添加字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/5
     */
    public function up()
    {
        $this->addColumn('{{%admin}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%admin}}',
            'venue_id'
        );
        $this->addColumn('{{%bind_pack}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%bind_pack}}',
            'venue_id'
        );
        $this->addColumn('{{%cabinet}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%cabinet}}',
            'venue_id'
        );
        $this->addColumn('{{%card_category_type}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%card_category_type}}',
            'venue_id'
        );
        $this->addColumn('{{%config}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%config}}',
            'venue_id'
        );
        $this->addColumn('{{%course}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%course}}',
            'venue_id'
        );
        $this->addColumn('{{%deal}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%deal}}',
            'venue_id'
        );
        $this->addColumn('{{%deal_type}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%deal_type}}',
            'venue_id'
        );
        $this->addColumn('{{%employee}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%employee}}',
            'venue_id'
        );
        $this->addColumn('{{%group_class}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%group_class}}',
            'venue_id'
        );
        $this->addColumn('{{%member_card}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%member_card}}',
            'venue_id'
        );
        $this->addColumn('{{%server}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%server}}',
            'venue_id'
        );
    }
    /**
     * @数据库 - 回滚字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/5
     */
    public function down()
    {
        $this->dropColumn('{{%admin}}','venue_id');
        $this->dropColumn('{{%bind_pack}}','venue_id');
        $this->dropColumn('{{%cabinet}}','venue_id');
        $this->dropColumn('{{%card_category_type}}','venue_id');
        $this->dropColumn('{{%config}}','venue_id');
        $this->dropColumn('{{%course}}','venue_id');
        $this->dropColumn('{{%deal}}','venue_id');
        $this->dropColumn('{{%deal_type}}','venue_id');
        $this->dropColumn('{{%employee}}','venue_id');
        $this->dropColumn('{{%group_class}}','venue_id');
        $this->dropColumn('{{%member_card}}','venue_id');
        $this->dropColumn('{{%server}}','venue_id');

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
