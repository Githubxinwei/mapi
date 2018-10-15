<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%charge_group_class}}".
 *
 * @property string $id
 * @property string $start
 * @property string $end
 * @property string $class_date
 * @property string $created_at
 * @property integer $status
 * @property string $class_number_id
 * @property string $coach_id
 * @property string $company_id
 * @property string $venue_id
 */
class ChargeGroupClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_group_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start', 'end', 'created_at', 'status', 'class_number_id', 'coach_id', 'company_id', 'venue_id'], 'integer'],
            [['class_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'start' => '开始时间',
            'end' => '结束时间',
            'class_date' => '上课日期',
            'created_at' => '创建时间',
            'status' => '状态:1正常2预约3课中4下课5取消',
            'class_number_id' => '私课编号id',
            'coach_id' => '教练id',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
