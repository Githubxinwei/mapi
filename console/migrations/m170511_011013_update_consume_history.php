<?php

use yii\db\Migration;

class m170511_011013_update_consume_history extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_consumption_history(修改消费历史记录表)
     * @author Houkaixin <Houkaixin@itsports.club>
     * @create 2017/5/11
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%consumption_history}}', 'describe');
        $this->addColumn('{{%consumption_history}}','describe','json COMMENT "消费描述"');
    }

    public function down()
    {
        $this->dropColumn('{{%consumption_history}}', 'describe');
        $this->addColumn('{{%consumption_history}}','describe', 'text COMMENT "消费描述"');
    }

}
