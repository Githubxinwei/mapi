<?php

use yii\db\Migration;

class m170916_061218_add_card_category_renew extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_card_category表
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/8/17
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'ordinary_renewal', 'decimal(10,2) COMMENT "普通续费"');
        $this->addColumn('{{%card_category}}', 'validity_renewal', 'json COMMENT "有效期续费"');
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'ordinary_renewal');
        $this->dropColumn('{{%card_category}}', 'validity_renewal');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170916_061218_add_card_category_renew cannot be reverted.\n";

        return false;
    }
    */
}
