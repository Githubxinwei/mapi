<?php

use yii\db\Migration;

class m171219_120726_add_cabinet_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%cabinet}}','cabinet_month',"json default null COMMENT '租赁月数'");
        $this->addColumn('{{%cabinet}}','cabinet_money',"json default null COMMENT '租赁月数对应的金额'");
        $this->addColumn('{{%cabinet}}','cabinet_dis',"json default null COMMENT '折扣'");
        $this->alterColumn('{{%cabinet}}','deposit',"decimal(10,2) default null COMMENT '押金'");
        $this->alterColumn('{{%cabinet}}','give_month',"json default null  COMMENT '赠送月数'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}','cabinet_month');
        $this->dropColumn('{{%cabinet}}','cabinet_money');
        $this->dropColumn('{{%cabinet}}','cabinet_dis');
    }
	
}
