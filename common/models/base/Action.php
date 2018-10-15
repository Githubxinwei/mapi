<?php

namespace common\models\base;
use yii\behaviors\TimestampBehavior;
use Yii;
use common\models\relations\ActionRelations;

/**
 * This is the model class for table "{{%action}}".
 *
 * @property string $id 自增ID
 * @property string $cat_id 分类ID
 * @property int $type 类型，0其他，1有氧，2重量
 * @property int $unit 单位，1次，2秒
 * @property string $energy 热能消耗
 * @property string $ssentials 动作要领
 * @property string $r_example 正确示范
 * @property string $w_example 错误示范
 * @property string $video 错误示范
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property int $is_delete 0 未删除，1已删除
 * @property int $status 状态
 */
class Action extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','type','unit','energy','ssentials'], 'required'],
            [[ 'type', 'unit', 'energy', 'created_at', 'updated_at', 'is_delete', 'status'], 'integer'],
            [['title','ssentials', 'video'], 'string', 'max' => 255],
            [['title'], 'unique','message'=>'动作名已被占用！'],
        ];
    }

    /**
     * @inheritdo   c
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'title'=>'动作名称',
            //'cat_id' => '分类ID',
            'type' => '类型，0其他，1有氧，2重量',
            'unit' => '单位，1次，2秒',
            'energy' => '热能消耗',
            'ssentials' => '动作要领',
            'r_example' => '正确示范',
            'w_example' => '错误示范',
            'video' => '视频',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'is_delete' => '0 未删除，1已删除',
            'status' => '状态',
        ];
    }
    /**
     * @定义行为
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    # 创建之前
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    # 修改之前
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                #设置默认值
                'value' => time()
            ],
        ];
    }
}
