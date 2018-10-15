<?php

use yii\db\Migration;

class m170606_024551_create_goods extends Migration
{
    /**
     * @数据库 - 创建表 - cloud_goods 商品表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '商品表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%goods}}', [
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'goods_type_id' => $this->bigInteger()->unsigned()->comment('商品类型id'),
            'venueId' => $this->bigInteger()->unsigned()->comment('场馆id'),
            'companyId' => $this->bigInteger()->unsigned()->comment('公司id'),
            'goods_brand'=> $this->string(200)->comment('商品品牌'),
            'goods_name' => $this->string(200)->comment('商品名称'),
            'unit_price' => $this->string(200)->comment('商品单价'),
            'goods_model' => $this->string(200)->comment('商品型号'),
            'goods_producer' => $this->string(200)->comment('商品生产商'),
            'goods_supplier' => $this->string(200)->comment('商品供应商'),
            'create_time' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ],$tableOptions);
        $this->createIndex(
            'index_goods_type_id',
            '{{%goods}}',
            'goods_type_id'
        );
        $this->createIndex(
            'index_venueId',
            '{{%goods}}',
            'venueId'
        );
        $this->createIndex(
            'index_companyId',
            '{{%goods}}',
            'companyId'
        );
    }

    public function down()
    {
        $this->dropTable('{{%goods}}');
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
