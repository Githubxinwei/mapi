<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%app_card_discount}}".
 *
 * @property string $id
 * @property string $venue_id
 * @property double $discount
 * @property string $start
 * @property string $end
 * @property integer $status
 * @property string $create_at
 * @property string $update_at
 * @property string $no_discount_card
 * @property string $frozen
 */
class AppCardDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_card_discount}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['venue_id', 'start', 'end', 'status', 'create_at', 'update_at', 'frozen'], 'integer'],
            [['discount'], 'number'],
            [['no_discount_card'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'venue_id' => '场馆id',
            'discount' => '折扣',
            'start' => '售卖开始时间',
            'end' => '售卖结束时间',
            'status' => '状态:1审核中2已通过3未通过4已撤销',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
            'no_discount_card' => '不打折卡种',
            'frozen' => '冻结:1是2否',
        ];
    }
}
