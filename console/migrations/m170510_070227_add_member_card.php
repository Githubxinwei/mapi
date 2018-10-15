<?php

use yii\db\Migration;

class m170510_070227_add_member_card extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_member_card 会员卡表
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/5/10
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_card}}', 'consume_status', 'smallint(6) COMMENT "1定金 2:发卡3:续费4:回款"');
        $this->addColumn('{{%member_card}}', 'present_money', 'decimal(10,2) COMMENT "买赠金额"');
        $this->addColumn('{{%member_card}}', 'present_private_lesson', 'int(255) COMMENT "赠送私教课"');
        $this->addColumn('{{%member_card}}', 'describe', 'text COMMENT "例如:0217683 0000845 原价2188打九折"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_card}}', 'consume_status');
        $this->dropColumn('{{%member_card}}', 'present_money');
        $this->dropColumn('{{%member_card}}', 'present_private_lesson');
        $this->dropColumn('{{%member_card}}', 'describe');
    }
}
