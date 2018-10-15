<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%seat_type}}".
 *
 * @property string $id
 * @property string $name
 * @property string $classroom_id
 * @property string $total_rows
 * @property string $total_columns
 * @property string $create_at
 * @property string $update_at
 * @property string $company_id
 * @property string $venue_id
 */
class SeatType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seat_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classroom_id', 'total_rows', 'total_columns', 'create_at', 'update_at', 'company_id', 'venue_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '座位排次名称',
            'classroom_id' => '教室id',
            'total_rows' => '总排数',
            'total_columns' => '总列数',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
