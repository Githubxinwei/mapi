<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%deal}}".
 *
 * @property string $id 自增ID
 * @property string $name 名称
 * @property string $deal_type_id 类型id
 * @property string $create_at 创建时间
 * @property string $create_id 创建人id
 * @property string $deal_number 编号
 * @property string $company_id 公司id
 * @property string $venue_id 场馆id
 * @property string $intro 描述
 * @property int $type 1卡种类,2私课类
 */
class Deal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deal_type_id', 'create_at', 'create_id', 'company_id', 'venue_id', 'type'], 'integer'],
            [['intro'], 'string'],
            [['name', 'deal_number'], 'string', 'max' => 255],
            [['deal_number'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '名称',
            'deal_type_id' => '类型id',
            'create_at' => '创建时间',
            'create_id' => '创建人id',
            'deal_number' => '编号',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'intro' => '描述',
            'type' => '1卡种类,2私课类',
        ];
    }
}
