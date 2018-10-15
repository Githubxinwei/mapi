<?php

use yii\db\Migration;

class m170731_052134_add_cabinet_give_month extends Migration
{
    /**
     * @数据库 - 柜子管理 - 新增押金字段
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/7/13
     */
    public function up()
    {
        $this->addColumn('{{%cabinet}}','give_month',"int COMMENT '赠送月数'");
    }

    public function down()
    {
        $this->dropColumn('{{%cabinet}}','give_month');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170731_052134_add_cabinet_give_month cannot be reverted.\n";

        return false;
    }
    */
}
