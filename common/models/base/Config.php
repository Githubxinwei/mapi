<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property string $id
 * @property string $key
 * @property string $value
 * @property string $type
 * @property string $company_id
 * @property string $venue_id
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'venue_id'], 'integer'],
            [['key', 'value', 'type'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'key' => '名称',
            'value' => '值',
            'type' => '类型',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
