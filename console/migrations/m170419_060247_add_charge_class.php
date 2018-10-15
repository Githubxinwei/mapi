<?php

use yii\db\Migration;

class m170419_060247_add_charge_class extends Migration
{
    /**
     * @数据库 - 修改表 - charge_class
     * @author 朱梦珂 <zhumengke@itsport.club>
     * @create 2017/4/19
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%charge_class}}','not_reservation','integer COMMENT "不可预约时间(分钟)" ');
        $this->addColumn('{{%charge_class}}','not_cancel','integer COMMENT "不可取消时间(分钟)" ');
        $this->addColumn('{{%charge_class}}','reservation_days','integer COMMENT "可预约天数" ');
    }

    public function down()
    {
        $this->dropColumn('{{%charge_class}}',"not_reservation");
        $this->dropColumn('{{%charge_class}}',"not_cancel");
        $this->dropColumn('{{%charge_class}}',"reservation_days");
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
