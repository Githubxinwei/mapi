<?php

use yii\db\Migration;

class m170916_094253_add_sms_record extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_sms_record表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/8/28
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%sms_record}}','var', 'json COMMENT "发送的自定义参数"');
    }

    public function down()
    {
        $this->dropColumn('{{%sms_record}}','var');
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
