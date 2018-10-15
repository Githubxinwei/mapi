<?php

use yii\db\Migration;

class m170607_003926_add_cxabinet extends Migration
{
    /**
     * @数据库 -  柜子表- 添加字段（柜子型号，柜子类别）
     * @author houkaixin <houkaixin@itsports.club>
     * @create 2017/6/3
     */
    public function up()
    {
        $this->addColumn('{{%cabinet}}', 'cabinet_model',"smallint(6) COMMENT '柜子类型：(1:大柜2:中柜3:小柜)'");
        $this->addColumn('{{%cabinet}}', 'cabinet_type',"smallint(6) COMMENT '柜子类别：(1:临时2:正式)'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}', 'cabinet_model');
        $this->dropColumn('{{%cabinet}}', 'cabinet_type');
    }

}
