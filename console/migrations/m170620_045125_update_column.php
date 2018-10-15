<?php

use yii\db\Migration;

class m170620_045125_update_column extends Migration
{
    /**
     * @数据库 - 修改字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/20
     */
    public function up()
    {
        $this->alterColumn('{{%transfer_record}}', 'spare', "json NULL  COMMENT '备用(json) '");
        $this->dropColumn('{{%transfer_record}}', 'spare_one');
        $this->dropColumn('{{%transfer_record}}', 'spare_two');
    }
    /**
     * @数据库 - 回滚字段
     * @author huangpengju  <huangpengju@itsports.club>
     * @create 2017/6/20
     */
    public function down()
    {
        $this->alterColumn('{{%transfer_record}}', 'spare',"decimal(10,2) COMMENT '备用'");
        $this->addColumn('{{%transfer_record}}', 'spare_one',"decimal(10,2) COMMENT '备用1'");
        $this->addColumn('{{%transfer_record}}', 'spare_two',"decimal(10,2) COMMENT '备用2'");
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
