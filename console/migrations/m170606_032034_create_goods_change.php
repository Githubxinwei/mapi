<?php

use yii\db\Migration;

class m170606_032034_create_goods_change extends Migration
{
    /**
     * @数据库 - 创建表 - goods_change 商品 进货出货表（明细表）
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '商品出入，入库变更明细表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%goods_change}}',[
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'goods_id' => $this->bigInteger()->unsigned()->comment('商品id'),
            'status'   => $this->smallInteger()->comment('状态1:入库 2：出库 3：报损 4:退库 5:报溢'),
            'operation_num' => $this->bigInteger()->unsigned()->comment('操作数量'),
            'list_num' => $this->string(200)->comment('商品单号'),
            'unit'=> $this->string(200)->comment('单位'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
            'describe' => $this->text()->comment('描述'),
        ],$tableOptions);
        $this->createIndex(
            'index_goods_id',
            '{{%goods_change}}',
            'goods_id'
        );
    }
    public function down()
    {
        $this->dropTable('{{%goods_change}}');
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
