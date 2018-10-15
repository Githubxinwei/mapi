<?php

use yii\db\Migration;

class m170414_095301_add_card_category extends Migration
{
    /**
     * @数据库 - 卡种表 -  添加字段
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function up()
    {
        $this->addColumn('{{%card_category}}', 'renew_price', "decimal(10,2)  COMMENT '续费金额'");
        $this->addColumn('{{%card_category}}', 'leave_least_Days', "int(11)  COMMENT '每次请假最少天数'");
        $this->dropColumn('{{%card_category}}','duration');
        $this->addColumn('{{%card_category}}','duration','JSON COMMENT \'有效期/时长(json["秒"=>,"分钟"=>"小时"=>"天"=>"周"=>"月"=>"季度"=>"年"=>])\'');

    }
    /**
     * @数据库 - 卡种表 -  回滚字段
     * @author Huang Pengju <huangpengju@itsport.club>
     * @create 2017/4/14
     */
    public function down()
    {
        $this->dropColumn('{{%card_category}}', 'renew_price');
        $this->dropColumn('{{%card_category}}', 'leave_least_Days');
        $this->dropColumn('{{%card_category}}','duration');
        $this->addColumn('{{%card_category}}','duration','JSON COMMENT \'次数(json["秒"=>1,"分钟"=>"小时"=>"天"=>"周"=>"月"=>"季度"=>"年"=>])\'');
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
