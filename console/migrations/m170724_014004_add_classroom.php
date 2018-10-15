<?php

use yii\db\Migration;

class m170724_014004_add_classroom extends Migration
{
    /**
     * @数据库 - 教室表 - 新增教室列数，教室行数
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/7/24
     */
    public function up()
    {
        $this->addColumn('{{%classroom}}','seat_columns',"bigInt COMMENT '教室座位列数'");
        $this->addColumn('{{%classroom}}','seat_rows',"bigInt COMMENT '教室座位行数'");
    }

    public function down()
    {
        $this->dropColumn('{{%classroom}}','seat_columns');
        $this->dropColumn('{{%classroom}}','seat_rows');
    }
}
