<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_behavior_trail}}".
 *
 * @property string $id
 * @property string $employee_id
 * @property integer $behavior
 * @property string $module_id
 * @property string $create_at
 * @property string $behavior_ip
 * @property string $behavior_intro
 */
class MemberBehaviorTrail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_behavior_trail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'behavior', 'module_id', 'create_at'], 'integer'],
            [['behavior_intro'], 'string'],
            [['behavior_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'employee_id' => '员工id',
            'behavior' => '1:浏览 2:编辑 3:修改 4:查看 5:删除',
            'module_id' => '操作模块id',
            'create_at' => '创建时间',
            'behavior_ip' => '行为ip',
            'behavior_intro' => '行为描述',
        ];
    }
}
