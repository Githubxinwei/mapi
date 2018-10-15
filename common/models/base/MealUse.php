<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%meal_use}}".
 *
 * @property string $id
 * @property string $employee_id
 * @property string $plan_id
 * @property string $plan_name
 * @property integer $status
 * @property string $create_at
 * @property string $use_start_time
 * @property string $use_send_time
 */
class MealUse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meal_use}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'plan_id', 'status', 'create_at', 'use_start_time', 'use_send_time'], 'integer'],
            [['plan_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'employee_id' => '店长ID',
            'plan_id' => '方案ID',
            'plan_name' => '方案名称',
            'status' => '状态:1:审核中,2:已审核,3:停用',
            'create_at' => '创建时间',
            'use_start_time' => '使用开始时间',
            'use_send_time' => '使用截止时间',
        ];
    }
}
