<?php

use yii\db\Migration;

class m170502_062059_create_order extends Migration
{
    /**
     * @数据库 - 创建订单表
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'COMMENT="订单表" CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%order}}', [
            'id'              => $this->bigPrimaryKey()->comment('自增ID'),
            'venue_id'        =>$this->bigInteger()->unsigned()->notNull()->comment('售卖场馆id'),
            'member_id'       =>$this->bigInteger()->unsigned()->notNull()->comment('会员id'),
            'card_category_id'=>$this->bigInteger()->unsigned()->notNull()->comment('卡种id'),

            'total_price'     =>$this->decimal(10,2)->comment('总价'),

            'order_time'     =>  $this->bigInteger()->unsigned()->comment('订单创建时间'),
            'pay_money_time' =>  $this->bigInteger()->unsigned()->comment('付款时间'),
            'pay_money_mode' => $this->smallInteger()->comment('付款方式：1现金；2支付宝；3微信；4pos刷卡；'),
            'sell_people_id' => $this->bigInteger()->unsigned()->notNull()->comment('售卖人id'),
            'payee_id'       => $this->bigInteger()->unsigned()->comment('收款人id'),
            'create_id'      => $this->bigInteger()->unsigned()->comment('操作人id'),
            'status'         => $this->smallInteger()->comment('订单状态：1未付款；2已付款；3其他状态；'),

            'note'          => $this->text()->comment('备注'),
            'order_number'    => $this->string(255)->unique()->notNull()->comment('订单编号'),
        ], $tableOptions);
        $this->createIndex(
            'index_venue_id',
            '{{%order}}',
            'venue_id'
        );
        $this->createIndex(
            'index_member_id',
            '{{%order}}',
            'member_id'
        );
        $this->createIndex(
            'index_card_category_id',
            '{{%order}}',
            'card_category_id'
        );
        $this->createIndex(
            'index_sell_people_id',
            '{{%order}}',
            'sell_people_id'
        );
        $this->createIndex(
            'index_payee_id',
            '{{%order}}',
            'payee_id'
        );
        $this->createIndex(
            'index_create_id',
            '{{%order}}',
            'create_id'
        );
    }
    /**
     * @数据库 - 订单表 - 回滚
     * @author Huangpengju <Huangpengju@itsport.club>
     * @create 2017/5/2
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%order}}');
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
