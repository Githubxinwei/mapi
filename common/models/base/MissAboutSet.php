<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%miss_about_set}}".
 *
 * @property string $id
 * @property integer $course_type
 * @property integer $freeze_way
 * @property string $miss_about_times
 * @property double $freeze_day
 * @property string $punish_money
 * @property string $company_id
 * @property string $venue_id
 * @property string $create_at
 * @property integer $freeze_type
 */
class MissAboutSet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%miss_about_set}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_type', 'freeze_way', 'miss_about_times', 'company_id', 'venue_id', 'create_at', 'freeze_type'], 'integer'],
            [['freeze_day', 'punish_money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'course_type' => '课程类型 1:团课 2:私课',
            'freeze_way' => '冻结方式 1:当月冻结 2:自定义冻结天数',
            'miss_about_times' => '每月爽约次数',
            'freeze_day' => '1:按月冻结 2:按指定天数冻结',
            'punish_money' => '处罚金额',
            'company_id' => '公司ID',
            'venue_id' => '场馆ID',
            'create_at' => '创建时间',
            'freeze_type' => '1:按月冻结 2:按指定天数冻结',
        ];
    }
}
