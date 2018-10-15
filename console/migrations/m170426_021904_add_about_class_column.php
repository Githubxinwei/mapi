<?php

use yii\db\Migration;

class m170426_021904_add_about_class_column extends Migration
{
    /**
 * @数据库 - 约课记录表增加字段
 * @author Huangpengju <Huangpengju@itsport.club>
 * @create 2017/4/26
 * @inheritdoc
 */
    public function up()
    {
        $this->addColumn('{{%about_class}}', 'coach_id', 'bigint(20) UNSIGNED COMMENT "教练id"');
        $this->addColumn('{{%about_class}}', 'class_date', 'date COMMENT "上课日期"');
        $this->addColumn('{{%about_class}}', 'start', 'bigint(20) COMMENT "开始时间"');
        $this->addColumn('{{%about_class}}', 'end', 'bigint(20) COMMENT "结束时间"');
    }
    /**
     * @数据库 - 约课记录表回滚字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/4/26
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%about_class}}', 'coach_id');
        $this->dropColumn('{{%about_class}}', 'class_date');
        $this->dropColumn('{{%about_class}}', 'start');
        $this->dropColumn('{{%about_class}}', 'end');
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
