<?php

use yii\db\Migration;

class m170606_035635_create_goods_detail extends Migration
{
    /**
     * @数据库 - 创建表 - goods_detail 商品变更明细表（明细表）
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '商品库存详情表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%goods_detail}}',[
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'goods_id' => $this->bigInteger()->unsigned()->comment('商品id'),
            'storage_num' => $this->bigInteger()->unsigned()->comment('库存数量'),
            'unit'=> $this->string(200)->comment('单位'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ],$tableOptions);
        $this->createIndex(
            'index_goods_id',
            '{{%goods_detail}}',
            'goods_id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%goods_detail}}');
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
