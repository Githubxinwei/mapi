<?php

use yii\db\Migration;

class m170424_064417_add_organizationOne extends Migration
{
    /**
     * @数据库 - 组织架构增加字段 - area，address,phone,describe
     * @author 侯凯新 <houkaixin@itsport.club>
     * @create 2017/4/24
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%organization}}', 'area', 'varchar(255) COMMENT "场馆面积"');
        $this->addColumn('{{%organization}}', 'address', 'varchar(255) COMMENT "场馆地址"');
        $this->addColumn('{{%organization}}', 'phone', 'varchar(255) COMMENT "场馆电话"');
        $this->addColumn('{{%organization}}', 'describe', 'varchar(255) COMMENT "场馆描述"');
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}', 'area');
        $this->dropColumn('{{%organization}}', 'address');
        $this->dropColumn('{{%organization}}', 'phone');
        $this->dropColumn('{{%organization}}', 'describe');
    }
}
