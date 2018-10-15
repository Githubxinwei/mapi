<?php

use yii\db\Migration;

class m170826_034429_add_order extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_order表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/26
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'refund_note', 'text COMMENT "退款理由"');
        $this->addColumn('{{%order}}', 'refuse_note', 'text COMMENT "拒绝原因"');
        $this->addColumn('{{%order}}', 'apply_time', 'bigint COMMENT "申请退款时间"');
        $this->addColumn('{{%order}}', 'review_time', 'bigint COMMENT "审批时间"');
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'refund_note');
        $this->dropColumn('{{%order}}', 'refuse_note');
        $this->dropColumn('{{%order}}', 'apply_time');
        $this->dropColumn('{{%order}}', 'review_time');
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
