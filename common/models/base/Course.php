<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%course}}".
 *
 * @property string $id
 * @property string $pid
 * @property string $name
 * @property string $category
 * @property string $created_at
 * @property string $path
 * @property string $pic
 * @property string $course_desrc
 * @property integer $class_type
 * @property string $create_id
 * @property string $update_at
 * @property integer $course_duration
 * @property integer $people_limit
 * @property string $course_difficulty
 * @property string $company_id
 * @property string $venue_id
 * @property string $coach_id
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'created_at', 'class_type', 'create_id', 'update_at', 'course_duration', 'people_limit', 'company_id', 'venue_id'], 'integer'],
            [['path', 'create_id'], 'required'],
            [['path', 'course_desrc', 'coach_id'], 'string'],
            [['name', 'category'], 'string', 'max' => 200],
            [['pic', 'course_difficulty'], 'string', 'max' => 255],
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
            'name' => '课种名',
            'category' => '类别',
            'created_at' => '添加时间',
            'path' => '路径(json)',
            'pic' => '课种头像宣传图片',
            'course_desrc' => '课程介绍',
            'class_type' => '1私教 2团教',
            'create_id' => '创建人id',
            'update_at' => '修改时间',
            'course_duration' => '课程时长',
            'people_limit' => '人数上限',
            'course_difficulty' => '人数上限',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'coach_id' => '教练id',
        ];
    }
}
