<?php

use yii\db\Migration;

class m170710_072244_add_leave_record extends Migration
{
    /**
     * @数据库 - 请假表- 新增请假类型
     * @author houkaixin  <houkaixin@itsports.club>
     * @create 2017/7/10
     */
    public function up()
    {
        $this->addColumn('{{%leave_record}}','leave_property',"tinyInt(6) COMMENT '请假性质 1:挂起 2:正常请假'");
        $this->addColumn('{{%leave_record}}','leave_type',"tinyInt(6) COMMENT '请假类型 1:怀孕 2:伤病 3:其它'");
    }

    public function down()
    {
       $this->dropColumn('{{%leave_record}}','leave_property');
       $this->dropColumn('{{%leave_record}}','leave_type');
    }

}
