<?php

use yii\db\Migration;

class m170412_031915_add_card_category extends Migration
{
    /**
     * @数据库 - 修改表 - cloud_card_category 创建请假总天数、请假总次数、最长请假天数、最长请假次数 字段
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/12
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'leave_total_days', "int(11) UNSIGNED  NULL COMMENT '请假总天数'");
        $this->addColumn('{{%card_category}}', 'leave_total_times', "int(11) UNSIGNED  NULL COMMENT '请假总次数'");
        $this->addColumn('{{%card_category}}', 'leave_long_limit', "JSON  COMMENT '最长请假天数,最长请假次数[天，次],[天，次]'");
    }
    /**
     * @数据库 - 修改表 - cloud_card_category 回滚请假总天数、请假总次数、最长请假天数、最长请假次数 字段
     * @author Huang Pengju <Huang Pengju@itsport.club>
     * @create 2017/4/12
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'leave_total_days');
        $this->dropColumn('{{%card_category}}', 'leave_total_times');
        $this->dropColumn('{{%card_category}}', 'leave_long_limit');
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
