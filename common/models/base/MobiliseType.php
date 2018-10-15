<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%mobilise_type}}".
 *
 * @property string $id
 * @property string $mobilise_id
 * @property string $type
 * @property string $note
 * @property string $created_at
 * @property string $update_at
 * @property string $create_id
 */
class MobiliseType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mobilise_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobilise_id', 'type', 'created_at', 'update_at', 'create_id'], 'integer'],
            [['note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'mobilise_id' => '调拨ID',
            'type' => '状态1.已申请2.已通过3.已调拨',
            'note' => '备注',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
            'create_id' => '创建人ID',
        ];
    }
}
