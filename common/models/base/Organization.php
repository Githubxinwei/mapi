<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%organization}}".
 *
 * @property string $id
 * @property string $pid
 * @property string $name
 * @property integer $style
 * @property string $created_at
 * @property string $update_at
 * @property string $create_id
 * @property string $path
 * @property string $area
 * @property string $address
 * @property string $phone
 * @property string $describe
 * @property string $params
 * @property string $pic
 * @property string $code
 * @property integer $is_allowed_join
 * @property string $establish_time
 * @property integer $status
 * @property string $longitude
 * @property string $latitude
 * @property integer $identity
 * @property integer $venue_type
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'style', 'created_at', 'update_at', 'create_id', 'is_allowed_join', 'establish_time', 'status', 'identity', 'venue_type'], 'integer'],
            [['path', 'params'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['name', 'code'], 'string', 'max' => 200],
            [['area', 'address', 'phone', 'describe', 'pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'pid' => '父ID',
            'name' => '名称',
            'style' => '类型 1公司2场馆3部门',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
            'create_id' => '创建人id',
            'path' => '路径(json)',
            'area' => '场馆面积',
            'address' => '场馆地址',
            'phone' => '场馆电话',
            'describe' => '场馆描述',
            'params' => '参数 自定义属性 json([免费课量=>10,基础课量=>10])',
            'pic' => '场馆图片',
            'code' => '识别码',
            'is_allowed_join' => '是否查看(1可以查看2不可以查看)',
            'establish_time' => '成立时间',
            'status' => '状态：1正常；2停用',
            'longitude' => '经度',
            'latitude' => '纬度',
            'identity' => '1. 普通 ，2:尊爵等',
            'venue_type' => '场馆类型:1 综合馆 2:瑜伽馆 3:舞蹈馆 4:搏击馆 5:游泳馆 6:健身馆',
        ];
    }
}
