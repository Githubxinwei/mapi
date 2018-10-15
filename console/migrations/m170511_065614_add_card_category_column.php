<?php

use yii\db\Migration;

class m170511_065614_add_card_category_column extends Migration
{
    /**
     * @数据库 - 卡种表 - 添加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'renew_unit',"int(11) COMMENT '续费多长时间/天'");
    }
    /**
     * @数据库 - 卡种表 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'renew_unit');
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
