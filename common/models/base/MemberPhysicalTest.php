<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 12:03
 * Desc:体侧信息
 */

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_physical_test}}".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员ID
 * @property string $create_at 添加时间
 * @property string $storage 会员的体测信息和体适能
 * @property string $coach_id 教练ID
 * @property int $is_delete 软删标记
 * @property string $pid 项目ID
 * @property int $type 类型(1 体测, 2 体适能)
 */
class MemberPhysicalTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_physical_test}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'coach_id', 'is_delete', 'pid', 'type'], 'integer'],
            [['create_at'], 'safe'],
            [['storage'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员ID',
            'create_at' => '添加时间',
            'storage' => '会员的体测信息和体适能',
            'coach_id' => '教练ID',
            'is_delete' => '软删标记',
            'pid' => '项目ID',
            'type' => '类型(1 体测, 2 体适能)',
        ];
    }
}