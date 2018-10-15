<?php

use yii\db\Migration;

class m170425_020910_add_organization_column extends Migration
{
    /**
     * @数据库 - 组织架构增加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/4/25
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%organization}}', 'code', 'varchar(200) COMMENT "识别码"');
    }
    /**
     * @数据库 - 组织架构增加字段
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/4/25
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%organization}}', 'code');
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
