<?php

use yii\db\Migration;

class m170410_084159_update_card_time extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_card_time 修改start、end 字段  类型
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/10
     * @update 2017/4/12
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%card_time}}', 'start', "varchar(200) NULL  COMMENT '开始时间：每天的几点'");
        $this->alterColumn('{{%card_time}}', 'end', "varchar(200) NULL  COMMENT '结束时间：每天的几点'");
    }
    /**
     * @数据库 - 修改表 - cloud_card_time 回滚start、end 字段  类型
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/12
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('{{%card_time}}', 'start', "	int(11) UNSIGNED NULL  COMMENT '开始时间：每天的几点 '");
        $this->alterColumn('{{%card_time}}', 'end', "int(11) UNSIGNED  NULL  COMMENT '结束时间：每天的几点 '");
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
