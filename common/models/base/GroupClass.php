<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%group_class}}".
 *
 * @property string $id
 * @property integer $start
 * @property integer $end
 * @property string $class_date
 * @property string $created_at
 * @property integer $status
 * @property string $course_id
 * @property string $coach_id
 * @property string $classroom_id
 * @property string $create_id
 * @property string $difficulty
 * @property string $desc
 * @property string $pic
 * @property integer $class_limit_time
 * @property integer $cancel_limit_time
 * @property integer $least_people
 * @property string $company_id
 * @property string $venue_id
 * @property string $seat_type_id
 */
class GroupClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start', 'end', 'created_at', 'status', 'course_id', 'coach_id', 'classroom_id', 'create_id', 'difficulty', 'class_limit_time', 'cancel_limit_time', 'least_people', 'company_id', 'venue_id', 'seat_type_id'], 'integer'],
            [['class_date'], 'safe'],
            [['desc'], 'string', 'max' => 200],
            [['pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'start' => '开始上课时间',
            'end' => '结束时间',
            'class_date' => '上课日期',
            'created_at' => '创建时间',
            'status' => '状态 1正常2调课3旷课4请假',
            'course_id' => '课种ID',
            'coach_id' => '教练ID',
            'classroom_id' => '教室ID',
            'create_id' => '创建人ID(员工的ID)',
            'difficulty' => '课程难度（1低，2中，3高）',
            'desc' => '团课课程描述',
            'pic' => '团课课程图片',
            'class_limit_time' => '开课前多少分钟不能约课',
            'cancel_limit_time' => '开课前多少分钟不能取消约课',
            'least_people' => '最少开课人数',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'seat_type_id' => '座位排次id',
        ];
    }
}
