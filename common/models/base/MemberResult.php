<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "cloud_member_result".
 *
 * @property string $id
 * @property string $member_id 会员id
 * @property string $class_id 课程id
 * @property string $complete 训练完成率
 * @property int $calorie 卡路里消耗
 * @property int $motion 运动方式
 * @property int $motion_qd 运动强度
 * @property string $everyday 每日评估
 * @property string $diet
 * @property string $member_url 会员签字
 */
class MemberResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_member_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'class_id', 'complete', 'calorie', 'motion', 'motion_qd'], 'integer'],
            [['dite', 'member_url'], 'string'],
            [['everyday'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员id',
            'class_id' => '课程id',
            'complete' => '训练完成率',
            'calorie' => '卡路里消耗',
            'motion' => '运动方式',
            'motion_qd' => '运动强度',
            'everyday' => '每日评估',
            'dite' => 'Diet',
            'member_url' => '会员签字',
        ];
    }
}
