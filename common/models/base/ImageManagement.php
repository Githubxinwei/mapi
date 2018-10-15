<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%image_management}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $type
 * @property string $image_size
 * @property string $image_wide
 * @property string $image_height
 * @property string $created_at
 * @property string $update_at
 * @property string $url
 * @property string $create_id
 * @property string $company_id
 * @property string $venue_id
 */
class ImageManagement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_management}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'image_size', 'image_wide', 'image_height', 'created_at', 'update_at', 'create_id', 'company_id', 'venue_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'name' => '图片名',
            'type' => '状态：1团课,2私课,3场馆,4会员,5会员卡,6占位图',
            'image_size' => '图片大小,单位KB',
            'image_wide' => '图片宽,单位PX',
            'image_height' => '图片高,单位PX',
            'created_at' => '创建时间',
            'update_at' => '修改时间',
            'url' => '图片名',
            'create_id' => '创建人ID',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
        ];
    }
}
