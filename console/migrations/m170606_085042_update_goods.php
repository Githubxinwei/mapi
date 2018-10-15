<?php

use yii\db\Migration;

class m170606_085042_update_goods extends Migration
{
    /**
     * @数据库 - 修改字段名称
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%goods}}','venueId');
        $this->dropColumn('{{%goods}}','companyId');
        $this->dropColumn('{{%goods_type}}','venueId');
        $this->dropColumn('{{%goods_type}}','companyId');

        $this->addColumn('{{%goods}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%goods}}',
            'venue_id'
        );
        $this->addColumn('{{%goods}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%goods}}',
            'company_id'
        );

        $this->addColumn('{{%goods_type}}','venue_id',"bigint(20) COMMENT '场馆id'");
        $this->createIndex(
            'index_venue_id',
            '{{%goods_type}}',
            'venue_id'
        );
        $this->addColumn('{{%goods_type}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%goods_type}}',
            'company_id'
        );
    }

    public function down()
    {
        $this->dropColumn('{{%goods}}','venue_id');
        $this->dropColumn('{{%goods}}','company_id');
        $this->dropColumn('{{%goods_type}}','venue_id');
        $this->dropColumn('{{%goods_type}}','company_id');
        $this->addColumn('{{%goods}}','venueId',"bigint(20) COMMENT '场馆id'");
        $this->addColumn('{{%goods}}','companyId',"bigint(20) COMMENT '公司id'");
        $this->addColumn('{{%goods_type}}','venueId',"bigint(20) COMMENT '场馆id'");
        $this->addColumn('{{%goods_type}}','companyId',"bigint(20) COMMENT '公司id'");
    }
}
