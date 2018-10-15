<?php

use yii\db\Migration;

class m170424_091336_add_organizationTwo extends Migration
{
    /**
     * @数据库 - 组织架构增加字段
     * @author 侯凯新 <houkaixin@itsport.club>
     * @create 2017/4/24
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%organization}}', 'pic', 'varchar(255) COMMENT "场馆图片"');
        $this->addColumn('{{%organization}}', 'companyCode', 'varchar(255) COMMENT "公司编码"');
        $this->addColumn('{{%organization}}', 'companyAddress', 'varchar(255) COMMENT "公司地址"');
        $this->addColumn('{{%organization}}', 'companyPhone', 'varchar(255) COMMENT "公司电话"');
        $this->addColumn('{{%organization}}', 'responsiblePerson', 'varchar(255) COMMENT "公司负责人"');
        $this->addColumn('{{%organization}}', 'establishTime','varchar(255) COMMENT "公司成立时间"');
    }

    public function down()
    {
        $this->dropColumn('{{%organization}}', 'pic');
        $this->dropColumn('{{%organization}}', 'companyCode');
        $this->dropColumn('{{%organization}}', 'companyAddress');
        $this->dropColumn('{{%organization}}', 'companyPhone');
        $this->dropColumn('{{%organization}}', 'responsiblePerson');
        $this->dropColumn('{{%organization}}', 'establishTime');
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
