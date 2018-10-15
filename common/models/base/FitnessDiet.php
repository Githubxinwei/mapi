<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%fitness_diet}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $name
 * @property string $content
 * @property string $create_at
 * @property string $update_at
 * @property string $company_id
 * @property string $venue_id
 */
class FitnessDiet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fitness_diet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'create_at', 'update_at', 'company_id', 'venue_id'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'type' => '类型：1.健身目标,2.饮食计划',
            'name' => '名称',
            'content' => '内容',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
        ];
    }
}
