<?php

use yii\db\Migration;

class m170925_023451_add_miss_about_set extends Migration
{
    /**
     * @数据库 - 新增字段 - cloud_miss_about_set 爽约设置表
     * @author 侯凯新<hgoukaixin@itsport.club>
     * @create 2017/9/25
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%miss_about_set}}','freeze_type',"smallInt COMMENT '1:按月冻结 2:按指定天数冻结'");
        $this->alterColumn('{{%miss_about_set}}','freeze_day',"double(2)  COMMENT '1:按月冻结 2:按指定天数冻结'");
    }

    public function down()
    {
        $this->dropColumn('{{%miss_about_set}}','freeze_type');
    }
}
