<?php

namespace common\models\base;

use Yii;
use common\models\relations\ClassSaleVenueRelations;
/**
 * This is the model class for table "cloud_class_sale_venue".
 *
 * @property string $id
 * @property string $charge_class_id
 * @property string $venue_id
 * @property string $sale_num
 * @property string $sale_start_time
 * @property string $sale_end_time
 * @property integer $status
 */
class ClassSaleVenue extends \yii\db\ActiveRecord
{
    use ClassSaleVenueRelations;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cloud_class_sale_venue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge_class_id', 'venue_id'], 'required'],
            [['charge_class_id', 'venue_id', 'sale_num', 'sale_start_time', 'sale_end_time', 'status'], 'integer'],
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
            'venue_id' => '场馆ID',
            'sale_num' => '售卖数量',
            'sale_start_time' => '售卖开始时间',
            'sale_end_time' => '售卖结束时间',
            'status' => '状态：1私课，2团课',
        ];
    }
}
