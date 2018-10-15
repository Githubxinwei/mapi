<?php

use yii\db\Migration;

class m170412_102551_add_card_category_times extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_card_category 创建充值卡属性字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'person_times', "int(10) COMMENT '次卡每次多少人'");
    }
    /**
     * @数据库 - 修改表 - cloud_card_category 创建充值卡属性字段
     * @author lihuien <lihuien@itsport.club>
     * @create 2017/4/12
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'person_times');
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
