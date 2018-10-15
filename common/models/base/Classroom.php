<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%classroom}}".
 *
 * @property string $id
 * @property string $name
 * @property string $venue_id
 * @property string $total_seat
 * @property string $classroom_area
 * @property string $classroom_describe
 * @property string $classroom_pic
 * @property string $company_id
 * @property string $seat_columns
 * @property string $seat_rows
 */
class Classroom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%classroom}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'venue_id'], 'required'],
            [['venue_id', 'total_seat', 'company_id', 'seat_columns', 'seat_rows'], 'integer'],
            [['classroom_describe'], 'string'],
            [['name', 'classroom_area'], 'string', 'max' => 200],
            [['classroom_pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '名称 ',
            'venue_id' => '场馆id',
            'total_seat' => '总座位数',
            'classroom_area' => '教室面积',
            'classroom_describe' => '教室描述',
            'classroom_pic' => '教室图片',
            'company_id' => '公司id',
            'seat_columns' => '教室座位列数',
            'seat_rows' => '教室座位行数',
        ];
    }
}
