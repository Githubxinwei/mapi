<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $age
 * @property integer $sex
 * @property string $mobile
 * @property string $email
 * @property string $birth_time
 * @property string $organization_id
 * @property string $position
 * @property integer $status
 * @property string $entry_date
 * @property string $leave_date
 * @property string $create_id
 * @property string $salary
 * @property string $admin_user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $params
 * @property string $intro
 * @property integer $level
 * @property string $pic
 * @property integer $class_hour
 * @property integer $is_check
 * @property integer $is_pass
 * @property string $alias
 * @property string $work_time
 * @property string $company_id
 * @property string $venue_id
 * @property string $fingerprint
 * @property string $signature
 * @property string $work_date
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'organization_id', 'create_id'], 'required'],
            [['age', 'sex', 'organization_id', 'status', 'create_id', 'admin_user_id', 'created_at', 'updated_at', 'level', 'class_hour', 'is_check', 'is_pass', 'work_time', 'company_id', 'venue_id'], 'integer'],
            [['birth_time', 'entry_date', 'leave_date', 'work_date'], 'safe'],
            [['salary'], 'number'],
            [['params', 'intro', 'fingerprint', 'signature'], 'string'],
            [['name', 'mobile', 'email', 'position', 'pic'], 'string', 'max' => 200],
            [['alias'], 'string', 'max' => 255],
            [['mobile'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键自增',
            'name' => '姓名',
            'age' => '年龄',
            'sex' => '性别',
            'mobile' => '手机号',
            'email' => '邮箱',
            'birth_time' => '生日',
            'organization_id' => '部门id',
            'position' => '职务',
            'status' => '状态：1在职 2离职',
            'entry_date' => '任职日期',
            'leave_date' => '离职日期',
            'create_id' => '创建人ID',
            'salary' => '薪资',
            'admin_user_id' => '管理员',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'params' => '课时=>,基础课量等自定义参数',
            'intro' => '个人简介',
            'level' => '等级:0新员工1低级2中级3高级',
            'pic' => '头像',
            'class_hour' => '课时',
            'is_check' => '是否需要审核:1需要,0不需要',
            'is_pass' => '是否通过审核:1通过,0未通过',
            'alias' => '别名',
            'work_time' => '从业时间',
            'company_id' => '公司id',
            'venue_id' => '场馆id',
            'fingerprint' => '指纹',
            'signature' => '签名',
            'work_date' => '从业日期',
        ];
    }
}
