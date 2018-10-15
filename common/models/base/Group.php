<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "cloud_group".
 *
 * @property string $id 自增ID
 * @property string $pid 父ID
 * @property int $level 级别
 * @property string $title 分类名称
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property string $create_id 创建人ID
 * @property int $type 1 餐饮分组
 * @property string $company_id 公司ID
 * @property string $venue_id 场馆ID
 * @property int $is_delete 软删标记 0 未删除 1 已删除
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'level', 'create_id', 'type', 'company_id', 'venue_id', 'is_delete'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'level' => 'Level',
            'title' => 'Title',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'create_id' => 'Create ID',
            'type' => 'Type',
            'company_id' => 'Company ID',
            'venue_id' => 'Venue ID',
            'is_delete' => 'Is Delete',
        ];
    }
}
