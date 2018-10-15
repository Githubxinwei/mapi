<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%clock}}".
 *
 * @property string $id
 * @property string $employee_id
 * @property string $date
 * @property string $in_time
 * @property string $out_time
 */
class Clock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clock}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'in_time', 'out_time'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'employee_id' => '员工ID',
            'date' => '日期',
            'in_time' => '上班打卡时间',
            'out_time' => '下班打卡时间',
        ];
    }
}
