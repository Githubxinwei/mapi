<?php

use yii\db\Migration;

class m170526_010913_add_group_class extends Migration
{
    /**
     * @数据库 - 团课课程 -  增加属性字段
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/5/26
     */
    public function up()
    {
        $this->addColumn('{{%course}}', 'course_duration',"int(11) COMMENT '课程时长'");
        $this->addColumn('{{%course}}', 'people_limit',"int(11) COMMENT '人数上限'");
        $this->addColumn('{{%course}}', 'course_difficulty',"varchar(255) COMMENT '人数上限'");
    }

    public function down()
    {
        $this->dropColumn('{{%course}}', 'course_duration');
        $this->dropColumn('{{%course}}', 'people_limit');
        $this->dropColumn('{{%course}}', 'course_difficulty');
    }
}
