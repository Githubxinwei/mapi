<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/5/6
 * Time: 14:32
 */
namespace common\models\base;

use Yii;
class Advertising extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertising}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'setting_id','status','is_over','show_time','start','end','create_at','create_id','update_time'], 'integer'],
            [['name','photo','url','note',], 'string'],
            [['venue_ids'], 'json'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'setting_id' => '类型ID',
            'status' => '状态 0 不可用, 1 可用',
            'name' => '名称',
            'photo' => '图片地址',
            'url' => '链接地址',
            'is_over' => '是否可跳过 0 不可跳过, 1 可跳过',
            'show_time' => '展示秒数',
            'venue_ids' => '展示场馆IDS',
            'start' => '开始时间',
            'end' => '结束时间',
            'create_at' => '创建时间',
            'create_id' => '创建人ID',
            'update_time' => '更新时间',
            'note' => '备注',
        ];
    }
}