<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%seat}}".
 *
 * @property string $id
 * @property string $classroom_id
 * @property string $seat_type
 * @property string $seat_number
 * @property integer $rows
 * @property integer $columns
 * @property string $seat_type_id
 */
class Seat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classroom_id'], 'required'],
            [['classroom_id', 'seat_type', 'rows', 'columns', 'seat_type_id'], 'integer'],
            [['seat_number'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'classroom_id' => '教室id',
            'seat_type' => '类型：1普通，2VIP，3贵族',
            'seat_number' => '座位号',
            'rows' => '排数',
            'columns' => '列数',
            'seat_type_id' => '座位类型id',
        ];
    }
}
