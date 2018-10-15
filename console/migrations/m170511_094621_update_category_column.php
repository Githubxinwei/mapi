<?php

use yii\db\Migration;

class m170511_094621_update_category_column extends Migration
{
    /**
     * @数据库 - 卡种表 - 添加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%card_category}}', 'total_circulation',"bigint(20) COMMENT '卡的总发行量（-1代表不限）'");
    }
    /**
     * @数据库 - 卡种表 - 回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('{{%card_category}}', 'total_circulation',"bigint(20) UNSIGNED NULL COMMENT '总发行量'");
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
