<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%extension_record}}".
 *
 * @property string $id
 * @property string $course_order_id
 * @property string $course_name
 * @property integer $course_num
 * @property integer $postpone_day
 * @property string $due_day
 * @property string $remark
 * @property string $create_at
 * @property string $member_id
 * @property string $create_id
 */
class ExtensionRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%extension_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_order_id', 'course_num', 'postpone_day', 'due_day', 'create_at', 'member_id', 'create_id'], 'integer'],
            [['course_name', 'remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'course_order_id' => '课程订单Id',
            'course_name' => '课程名称',
            'course_num' => '课程节数',
            'postpone_day' => '延期天数',
            'due_day' => '到期日期',
            'remark' => '延期备注',
            'create_at' => '创建时间',
            'member_id' => '会员id',
            'create_id' => '创建人Id',
        ];
    }
}
