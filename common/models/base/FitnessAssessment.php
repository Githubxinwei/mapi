<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 17:09
 * Desc:体适能评估表
 */
namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%fitness_assessment}}".
 *
 * @property string $id 自增ID
 * @property string $pid 父ID
 * @property string $title 体测项目
 * @property string $unit 单位
 * @property string $normal_range 正常范围
 * @property int $level 级别
 * @property int $sort 排序
 * @property int $status 状态 0 启用, 1 禁用
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 */
class FitnessAssessment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fitness_assessment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'level', 'sort', 'status', 'create_at', 'update_at'], 'integer'],
            [['title', 'unit', 'normal_range'], 'string', 'max' => 255],
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
            'title' => '体测项目',
            'unit' => '单位',
            'normal_range' => '正常范围',
            'level' => '级别',
            'sort' => '排序',
            'status' => '状态 0 启用, 1 禁用',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }
}