<?php

namespace common\models\base;

use common\models\relations\ChargeClassServiceRelations;
use Yii;

/**
 * This is the model class for table "{{%charge_class_service}}".
 *
 * @property string $id
 * @property string $charge_class_id
 * @property string $service_id
 * @property string $gift_id
 * @property integer $type
 * @property integer $category
 * @property string $create_time
 * @property integer $service_num
 * @property integer $gift_num
 */
class ChargeClassService extends \yii\db\ActiveRecord
{
    use ChargeClassServiceRelations;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_class_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_class_id'], 'required'],
            [['charge_class_id', 'service_id', 'gift_id', 'type', 'category', 'create_time', 'service_num', 'gift_num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'charge_class_id' => '私课ID',
            'service_id' => '服务ID',
            'gift_id' => '赠品ID',
            'type' => '类型：1服务，2赠品',
            'category' => '类别：1私课，2团课',
            'create_time' => '创建时间',
            'service_num' => '服务数量',
            'gift_num' => '赠品数量',
        ];
    }
}
