<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%module}}".
 *
 * @property string $id
 * @property string $name
 * @property string $e_name
 * @property integer $level
 * @property string $pid
 * @property string $note
 * @property string $create_id
 * @property string $create_at
 * @property string $icon
 * @property string $url
 * @property string $update_at
 * @property integer $number
 * @property integer $is_show
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'e_name'], 'required'],
            [['level', 'pid', 'create_id', 'create_at', 'update_at', 'number', 'is_show'], 'integer'],
            [['name', 'e_name', 'note', 'icon', 'url'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '菜单名',
            'e_name' => '菜单英文名',
            'level' => '等级',
            'pid' => '父ID',
            'note' => '说明',
            'create_id' => '创建人',
            'create_at' => '创建时间',
            'icon' => '图标',
            'url' => '路由',
            'update_at' => '修改時間',
            'number' => '序号',
            'is_show' => '是否显示1.显示,2不显示',
        ];
    }
}
