<?php

use yii\db\Migration;

class m170606_043919_create_goods_type extends Migration
{
    /**
     * @数据库 - 创建表 - goods_type 商品类别表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/6/6
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = "COMMENT = '商品类别表' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        }
        $this->createTable('{{%goods_type}}',[
            'id' => $this->bigPrimaryKey()->comment('自增ID'),
            'venueId' => $this->bigInteger()->unsigned()->comment('场馆id'),
            'companyId' => $this->bigInteger()->unsigned()->comment('公司id'),
            'goods_type'=> $this->string(200)->comment('商品类别'),
            'create_at' => $this->bigInteger()->unsigned()->comment('创建时间'),
        ],$tableOptions);
        $this->createIndex(
            'index_venueId',
            '{{%goods_type}}',
            'venueId'
        );
        $this->createIndex(
            'index_companyId',
            '{{%goods_type}}',
            'companyId'
        );
    }
    public function down()
    {
        $this->dropTable('{{%goods_type}}');
    }
}
