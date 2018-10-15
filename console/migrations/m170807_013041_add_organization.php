<?php

use yii\db\Migration;

class m170807_013041_add_organization extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_organization表 新增字段
     * @author Huang hua <Huanghua@itsport.club>
     * @create 2017/8/7
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%organization}}', 'longitude', "decimal(12,8)  COMMENT '经度'");
        $this->addColumn('{{%organization}}', 'latitude', "decimal(12,8)  COMMENT '纬度'");
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}', 'longitude');
        $this->dropColumn('{{%organization}}', 'latitude');
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
