<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%bind_pack}}".
 *
 * @property string $id
 * @property string $card_category_id
 * @property string $polymorphic_id
 * @property string $polymorphic_type
 * @property integer $number
 * @property integer $status
 * @property string $company_id
 * @property string $venue_id
 * @property string $polymorphic_ids
 */
class BindPack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bind_pack}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_category_id', 'polymorphic_id'], 'required'],
            [['card_category_id', 'polymorphic_id', 'number', 'status', 'company_id', 'venue_id'], 'integer'],
            [['polymorphic_ids'], 'string'],
            [['polymorphic_type'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'card_category_id' => '卡种id',
            'polymorphic_id' => '多态id(课程id、服务id、扣费项目id、赠品id)',
            'polymorphic_type' => '多态类型（比如：表名字）',
            'number' => '数量',
            'status' => '状态1:课程 2：服务 3：扣费 4:赠品',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'polymorphic_ids' => '多选JSON存储id',
        ];
    }
}
