<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 13:07
 * Desc:用户手机通讯录
 */
namespace common\models\base;

use Yii;
/**
 * This is the model class for table "{{%app_user_contact}}".
 *
 * @property string $id 自增ID
 * @property string $member_id 会员ID
 * @property string $company_id 公司ID
 * @property string $name 姓名
 * @property string $phone 手机号
 * @property string $create_at 请求时间
 * @property string $note 备注
 * @property int $is_delete 软删标记
 */
class AppUserContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_user_contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'company_id', 'is_delete'], 'integer'],
            [['create_at'], 'safe'],
            [['name', 'phone'], 'string', 'max' => 120],
            [['note'], 'string', 'max' => 255],
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
            'company_id' => '公司ID',
            'name' => '姓名',
            'phone' => '手机号',
            'create_at' => '请求时间',
            'note' => '备注',
            'is_delete' => '软删标记',
        ];
    }
}