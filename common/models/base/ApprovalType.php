<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%approval_type}}".
 *
 * @property string $id
 * @property string $type
 * @property string $company_id
 * @property string $venue_id
 * @property string $create_at
 */
class ApprovalType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%approval_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'venue_id', 'create_at'], 'integer'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'type' => '审批类型:1新增卡种',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'create_at' => '创建时间',
        ];
    }
}
