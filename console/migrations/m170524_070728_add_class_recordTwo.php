<?php

use yii\db\Migration;

class m170524_070728_add_class_recordTwo extends Migration
{
    /**
     * @数据库 - 添加字段 - cloud_class_record上课记录表
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/5/24
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%class_record}}', 'coach_id',"bigint(20) COMMENT '教练id'");
        $this->addColumn('{{%class_record}}', 'start',"bigint(20) COMMENT '开始时间'");
        $this->addColumn('{{%class_record}}', 'end',"bigint(20) COMMENT '结束时间'");
    }


    public function down()
    {
        $this->dropColumn('{{%class_record}}', 'coach_id');
        $this->dropColumn('{{%class_record}}', 'start');
        $this->dropColumn('{{%class_record}}', 'end');
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
