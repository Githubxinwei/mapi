<?php

use yii\db\Migration;

class m170905_074815_add_cardcard_category_is_app_show extends Migration
{
    /**
     * @数据库 - 新增会员表字段 - cloud_member_details表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/2
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'is_app_show', 'tinyint(4) default "2" COMMENT "是否在app显示1:显示,2:不显示"');
    }

    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'is_app_show');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170905_074815_add_cardcard_category_is_app_show cannot be reverted.\n";

        return false;
    }
    */
}
