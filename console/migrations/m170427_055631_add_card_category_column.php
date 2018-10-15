<?php

use yii\db\Migration;

class m170427_055631_add_card_category_column extends Migration
{
    /**
     * @数据库 - 卡种表增加合同、别名字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/4/26
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}','deal_id','bigint(20) UNSIGNED NOT NULL COMMENT "合同id"');
        $this->addColumn('{{%card_category}}','another_name','varchar(200)  COMMENT "另一个卡名"');
    }
    /**
     * @数据库 - 卡种表回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/4/26
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}','deal_id');
        $this->dropColumn('{{%card_category}}','another_name');
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
