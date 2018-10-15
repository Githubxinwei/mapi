<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $child
 * @property string $arrchildid
 * @property integer $key_id
 * @property integer $listorder
 * @property string $description
 * @property string $setting
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'child', 'key_id', 'listorder'], 'integer'],
            [['name', 'arrchildid', 'description', 'setting'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增Id',
            'name' => '名称',
            'parent_id' => '父级ID',
            'child' => '是否有下级',
            'arrchildid' => '下级列表',
            'key_id' => 'KEY',
            'listorder' => '排序',
            'description' => '描述',
            'setting' => '设置',
        ];
    }
}
