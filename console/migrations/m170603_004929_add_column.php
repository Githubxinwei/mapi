<?php

use yii\db\Migration;

class m170603_004929_add_column extends Migration
{
    /**
     * @数据库 - 添加字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/3
     */
    public function up()
    {
        $this->addColumn('{{%admin}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%admin}}',
            'company_id'
        );
        $this->addColumn('{{%bind_pack}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%bind_pack}}',
            'company_id'
        );
        $this->addColumn('{{%cabinet}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%cabinet}}',
            'company_id'
        );
        $this->addColumn('{{%cabinet_type}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%cabinet_type}}',
            'company_id'
        );
        $this->addColumn('{{%card_category}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%card_category}}',
            'company_id'
        );
        $this->addColumn('{{%card_category_type}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%card_category_type}}',
            'company_id'
        );
        $this->addColumn('{{%charge_class}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%charge_class}}',
            'company_id'
        );
        $this->addColumn('{{%classroom}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%classroom}}',
            'company_id'
        );
        $this->addColumn('{{%config}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%config}}',
            'company_id'
        );
        $this->addColumn('{{%consumption_history}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%consumption_history}}',
            'company_id'
        );
        $this->addColumn('{{%course}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%course}}',
            'company_id'
        );
        $this->addColumn('{{%deal}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%deal}}',
            'company_id'
        );
        $this->addColumn('{{%deal_type}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%deal_type}}',
            'company_id'
        );
        $this->addColumn('{{%employee}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%employee}}',
            'company_id'
        );
        $this->alterColumn('{{%employee}}', 'organization_id', "bigint(20) UNSIGNED NOT NULL  COMMENT '部门id'");
        $this->addColumn('{{%group_class}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%group_class}}',
            'company_id'
        );
        $this->addColumn('{{%member}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%member}}',
            'company_id'
        );
        $this->addColumn('{{%member_card}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%member_card}}',
            'company_id'
        );
        $this->addColumn('{{%order}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%order}}',
            'company_id'
        );
        $this->addColumn('{{%server}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%server}}',
            'company_id'
        );
        $this->addColumn('{{%venue_limit_times}}','company_id',"bigint(20) COMMENT '公司id'");
        $this->createIndex(
            'index_company_id',
            '{{%venue_limit_times}}',
            'company_id'
        );
    }
    /**
     * @数据库 - 回滚字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/3
     */
    public function down()
    {
        $this->dropColumn('{{%admin}}','company_id');
        $this->dropColumn('{{%bind_pack}}','company_id');
        $this->dropColumn('{{%cabinet}}','company_id');
        $this->dropColumn('{{%cabinet_type}}','company_id');
        $this->dropColumn('{{%card_category}}','company_id');
        $this->dropColumn('{{%card_category_type}}','company_id');
        $this->dropColumn('{{%charge_class}}','company_id');
        $this->dropColumn('{{%classroom}}','company_id');
        $this->dropColumn('{{%config}}','company_id');
        $this->dropColumn('{{%consumption_history}}','company_id');
        $this->dropColumn('{{%course}}','company_id');
        $this->dropColumn('{{%deal}}','company_id');
        $this->dropColumn('{{%deal_type}}','company_id');
        $this->dropColumn('{{%employee}}','company_id');
        $this->alterColumn('{{%employee}}', 'organization_id', "bigint(20) UNSIGNED NOT NULL  COMMENT '组织ID'");
        $this->dropColumn('{{%group_class}}','company_id');
        $this->dropColumn('{{%member}}','company_id');
        $this->dropColumn('{{%member_card}}','company_id');
        $this->dropColumn('{{%order}}','company_id');
        $this->dropColumn('{{%server}}','company_id');
        $this->dropColumn('{{%venue_limit_times}}','company_id');
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
