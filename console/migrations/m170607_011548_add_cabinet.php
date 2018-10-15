<?php

use yii\db\Migration;

class m170607_011548_add_cabinet extends Migration
{
    /**
     * @数据库 -  柜子表- 添加字段（半年租金价格）
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/12
     */
    public function up()
    {
        $this->addColumn('{{%cabinet}}', 'halfYearRentPrice',"decimal(10,2) COMMENT '半年租价'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}', 'halfYearRentPrice');
    }


}
