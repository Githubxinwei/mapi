<?php

use yii\db\Migration;

class m170605_015350_add_cabinet_type extends Migration
{
    /**
     * @数据库 -  柜子类型表 - 添加字段（柜子型号，柜子类别）
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/3
     */
    public function up()
    {
        $this->addColumn('{{%cabinet_type}}', 'cabinet_model',"smallint(6) COMMENT '柜子类型：(1:大柜2:中柜3:小柜)'");
        $this->addColumn('{{%cabinet_type}}', 'cabinet_type',"smallint(6) COMMENT '柜子类别：(1:临时2:正式)'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet_type}}', 'cabinet_model');
        $this->dropColumn('{{%cabinet_type}}', 'cabinet_type');
    }
}
