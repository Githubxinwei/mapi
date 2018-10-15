<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%machine_record}}".
 *
 * @property string $id
 * @property string $ip
 * @property string $machine_model
 * @property string $machine_type
 * @property string $machine_status
 * @property string $venue_id
 * @property string $company_id
 */
class MachineRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machine_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'company_id'], 'integer'],
            [['ip', 'machine_model', 'machine_type', 'machine_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'ip' => 'ip地址',
            'machine_model' => '机器型号',
            'machine_type' => '机器型号',
            'machine_status' => '机器状态 1:正常 2不正常',
            'venue_id' => '场馆id',
            'company_id' => '公司id',
        ];
    }
}
