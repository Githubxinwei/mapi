<?php

namespace common\models\base;

use common\behaviors\PositionBehavior;
use common\behaviors\CacheInvalidateBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;


class ActionCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status', 'created_at', 'pid','updated_at', 'level','sort'], 'integer'],
            [['title'], 'string', 'max' => 25],
            [['title'], 'unique'],
            ['pid', 'default', 'value' => 0],
            [['sort'], 'default', 'value' => 0]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID自增',
            'title' => '分类名称',
            'p_title' => '分类名称',
            'level' => '级别',
            'pid' => '父ID',
            'ptitle' => '上级分类', // 非表字段,方便后台显示
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态',
            'is_delete' => '0 未删除，1已删除',
            'sort' => '排序',
            'module' => '支持的文档类型',
        ];
    }

    public function actions()
    {
        return [
            'position' => [
                'class' => 'backend\\actions\\Position',
                'returnUrl' => Url::current()
            ]
        ];
    }
    /**
     * @定义行为
     */
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => TimestampBehavior::className(),
//                'attributes' => [
//                    # 创建之前
//                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
//                    # 修改之前
//                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
//                ],
//                #设置默认值
//                'value' => time()
//            ],
//            'positionBehavior' => [
//                'class' => PositionBehavior::className(),
//                'positionAttribute' => 'sort',
//                'groupAttributes' => [
//                    'pid'
//                ],
//            ],
//            /*[
//                'class' => CacheInvalidateBehavior::className(),
//                'tags' => [
//                    'categoryList'
//                ]
//
//            ]*/
//
//        ];
//    }
   /* public function afterFind()
    {
        parent::afterFind();
        $this->created_at = date('Y-m-d',$this->created_at);
        $this->updated_at = date('Y-m-d',$this->updated_at);
    }*/
}
