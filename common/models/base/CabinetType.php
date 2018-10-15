<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%cabinet_type}}".
 *
 * @property string $id
 * @property string $type_name
 * @property integer $sex
 * @property string $created_at
 * @property string $venue_id
 * @property string $company_id
 * @property integer $cabinet_model
 * @property integer $cabinet_type
 */
class CabinetType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cabinet_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name', 'sex'], 'required'],
            [['sex', 'created_at', 'venue_id', 'company_id', 'cabinet_model', 'cabinet_type'], 'integer'],
            [['type_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type_name' => '类型名称',
            'sex' => '状态：1男，2女',
            'created_at' => '创建时间',
            'venue_id' => '场馆id',
            'company_id' => '公司id',
            'cabinet_model' => '柜子类型：(1:大柜2:中柜3:小柜)',
            'cabinet_type' => '柜子类别：(1:临时2:正式)',
        ];
    }
}
