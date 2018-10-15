<?php

use yii\db\Migration;

class m171016_082534_update_aboutClass extends Migration
{
    /**
     * @数据库 - 修改字段 - cloud_about_class 修改会员预约记录表
     * @author houkaixn <houkaixn@itsports.club>
     * @create 2017/10/16
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%about_class}}','status',"smallInt  COMMENT '1:未上课 2:取消预约 3:上课中 4:下课 5:过期 6:旷课(卡未被冻结) 7:旷课(团课爽约)'");
    }

    public function down()
    {
        $this->alterColumn('{{%about_class}}','status',"smallInt  COMMENT '1:未上课 2:取消预约 3:上课中 4:下课 5:过期 6:旷课(卡未被冻结) 7:旷课(团课爽约)'");
    }
}
