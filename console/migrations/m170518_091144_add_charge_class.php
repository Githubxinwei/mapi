<?php

use yii\db\Migration;

class m170518_091144_add_charge_class extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_charge_class(付费课程)
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/5/18
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%charge_class}}', 'private_class_type','smallint COMMENT "收银类型（1.PT 2.其它）"');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}', 'private_class_type');
    }
}
