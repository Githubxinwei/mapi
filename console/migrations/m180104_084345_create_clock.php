<?php

use yii\db\Migration;

/**
 * Class m180104_084345_create_clock
 */
class m180104_084345_create_clock extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = "COMMENT = '员工(私教)打卡表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%clock}}', [
            'id'          => $this->bigPrimaryKey()->comment('自增ID'),
            'employee_id' => $this->bigInteger()->comment('员工ID'),
            'date'        => $this->date()->comment('日期'),
            'in_time'     => $this->bigInteger()->notNull()->defaultValue(0)->comment('上班打卡时间'),
            'out_time'    => $this->bigInteger()->notNull()->defaultValue(0)->comment('下班打卡时间'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable("{{%clock}}");
    }
}
