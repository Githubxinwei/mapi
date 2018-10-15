<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%consultant_change_record}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $create_id
 * @property string $created_at
 * @property string $consultant_id
 * @property integer $venue_id
 * @property integer $company_id
 * @property integer $behavior
 */
class ConsultantChangeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%consultant_change_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'create_id', 'created_at', 'consultant_id', 'venue_id', 'company_id', 'behavior'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'member_id' => '会员Id',
            'create_id' => '操作人Id',
            'created_at' => '创建时间',
            'consultant_id' => '会籍顾问Id',
            'venue_id' => '场馆Id',
            'company_id' => '公司Id',
            'behavior' => '行为:1入馆2办卡3修改4续费5升级',
        ];
    }
}
