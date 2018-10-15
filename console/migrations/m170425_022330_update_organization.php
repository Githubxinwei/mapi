<?php

use yii\db\Migration;

class m170425_022330_update_organization extends Migration
{
    /**
     * @数据库 - 组织架构删除organization相关字段
     * @author 侯凯新 <houkaixin@itsport.club>
     * @create 2017/4/24
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%organization}}', 'companyCode');
        $this->dropColumn('{{%organization}}', 'companyAddress');
        $this->dropColumn('{{%organization}}', 'companyPhone');
        $this->dropColumn('{{%organization}}', 'responsiblePerson');
        $this->dropColumn('{{%organization}}', 'establishTime');
    }

    public function down()
    {
        $this->addColumn('{{%organization}}', 'companyCode', 'varchar(255) COMMENT "公司编码"');
        $this->addColumn('{{%organization}}', 'companyAddress', 'varchar(255) COMMENT "公司地址"');
        $this->addColumn('{{%organization}}', 'companyPhone', 'varchar(255) COMMENT "公司电话"');
        $this->addColumn('{{%organization}}', 'responsiblePerson', 'varchar(255) COMMENT "公司负责人"');
        $this->addColumn('{{%organization}}', 'establishTime','varchar(255) COMMENT "公司成立时间"');
    }
    
}
