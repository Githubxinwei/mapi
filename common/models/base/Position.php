<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%position}}".
 *
 * @property string $id
 * @property string $name
 * @property string $note
 * @property string $venue_id
 * @property string $department_id
 * @property string $company_id
 * @property string $create_at
 * @property string $update_at
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'department_id', 'company_id', 'create_at', 'update_at'], 'integer'],
            [['name', 'note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'name' => '职位名称',
            'note' => '备注',
            'venue_id' => '场馆id',
            'department_id' => '部门id',
            'company_id' => '公司id',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
        ];
    }
}
