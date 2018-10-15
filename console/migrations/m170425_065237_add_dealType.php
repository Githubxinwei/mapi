<?php

use yii\db\Migration;

class m170425_065237_add_dealType extends Migration
{
    /**
     * @数据库 - 合同类型表增加字段
     * @author houkaixin <houkaixin@itsport.club>
     * @create 2017/4/25
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%deal_type}}', 'type_name', 'varchar(200) COMMENT "合同类型名字"');
    }

    public function down()
    {
        $this->dropColumn('{{%deal_type}}', 'type_name');

    }

}
