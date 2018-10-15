<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%coach_class_record}}".
 *
 * @property string $id
 * @property string $sign_time
 * @property string $class_over_time
 * @property string $class_id
 * @property string $coach_id
 * @property integer $status
 */
class CoachClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coach_class_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sign_time', 'class_over_time', 'class_id', 'coach_id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'sign_time' => '签到时间',
            'class_over_time' => '下课时间',
            'class_id' => '排课记录表id',
            'coach_id' => '教练id',
            'status' => '教练上课状态 1:未打卡 2:已打卡 3:已下课 4:未上课',
        ];
    }
}
