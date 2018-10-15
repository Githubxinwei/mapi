<?php

use yii\db\Migration;

class m170903_092040_add_member_fingerprint extends Migration
{
    /**
     * @数据库 - 新增会员表字段 - cloud_member_details表
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/9/2
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%member_details}}', 'fingerprint', 'text COMMENT "指纹"');
    }

    public function down()
    {
        $this->dropColumn('{{%member_details}}', 'fingerprint');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170903_092040_add_member_fingerprint cannot be reverted.\n";

        return false;
    }
    */
}
