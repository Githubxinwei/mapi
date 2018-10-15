<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $from
 * @property integer $company_id
 * @property integer $venue_id
 * @property integer $user_id
 * @property string $content
 * @property string $occurred_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $pics
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'company_id', 'venue_id', 'user_id', 'occurred_at', 'created_at', 'updated_at'], 'integer'],
            [['content', 'pics'], 'string'],
            [['from'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type_id' => '类型ID',
            'from' => '来源产品代号[android_customer->安卓会员端,ios_business->IOS管理端,ios_coach->IOS私教端,ios_group->IOS团教端]',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'user_id' => '用户ID(member_id或employee_id)',
            'content' => '内容',
            'occurred_at' => '故障发生时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'pics' => '多张图片',
        ];
    }
}
