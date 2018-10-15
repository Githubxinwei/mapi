<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%cabinet}}".
 *
 * @property string $id
 * @property string $cabinet_type_id
 * @property string $cabinet_number
 * @property integer $status
 * @property string $creater_id
 * @property string $created_at
 * @property string $update_at
 * @property string $dayRentPrice
 * @property string $monthRentPrice
 * @property string $yearRentPrice
 * @property string $company_id
 * @property string $venue_id
 * @property integer $cabinet_model
 * @property integer $cabinet_type
 * @property string $halfYearRentPrice
 * @property string $deposit
 * @property string $give_month
 * @property string $cabinet_month
 * @property string $cabinet_money
 * @property string $cabinet_dis
 */
class Cabinet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cabinet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cabinet_type_id', 'cabinet_number', 'creater_id'], 'required'],
            [['cabinet_type_id', 'status', 'creater_id', 'created_at', 'update_at', 'company_id', 'venue_id', 'cabinet_model', 'cabinet_type'], 'integer'],
            [['dayRentPrice', 'monthRentPrice', 'yearRentPrice', 'halfYearRentPrice', 'deposit'], 'number'],
            [['give_month', 'cabinet_month', 'cabinet_money', 'cabinet_dis'], 'string'],
            [['cabinet_number'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'cabinet_type_id' => '柜子类型ID',
            'cabinet_number' => '柜子编号',
            'status' => '状态：1未租，2已租，3维修中',
            'creater_id' => '创建人ID',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
            'dayRentPrice' => '薪资',
            'monthRentPrice' => '薪资',
            'yearRentPrice' => '薪资',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'cabinet_model' => '柜子类型：(1:大柜2:中柜3:小柜)',
            'cabinet_type' => '柜子类别：(1:临时2:正式)',
            'halfYearRentPrice' => '半年租价',
            'deposit' => '押金',
            'give_month' => '赠送月数',
            'cabinet_month' => '租赁月数',
            'cabinet_money' => '租赁月数对应的金额',
            'cabinet_dis' => '折扣',
        ];
    }
}
