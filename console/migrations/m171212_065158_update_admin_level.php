<?php

use yii\db\Migration;

/**
 * Class m171212_065158_update_admin_level
 */
class m171212_065158_update_admin_level extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_course_package_detail 修改单节原价金额数据类型
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/11/24
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%admin}}','level',"int(11)  COMMENT '管理权限级别'");
    }

    public function down()
    {
        $this->alterColumn('{{%admin}}','level',"int(11)  COMMENT '管理权限级别'");
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171212_065158_update_admin_level cannot be reverted.\n";

        return false;
    }
    */
}
