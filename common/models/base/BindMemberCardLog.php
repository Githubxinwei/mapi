<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/4/17
 * Time: 13:39
 * Desc:会员绑卡日志记录模型
 */
namespace common\models\base;

use Yii;
/**
 * This is the model class for table "{{%bind_member_card_log}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $document_type
 * @property string $id_card
 * @property integer $status
 * @property integer $card_number
 * @property integer $create_at
 * @property integer $note
 * @property string $source
 */
class BindMemberCardLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bind_member_card_log}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'document_type','id_card','card_number'], 'required'],
            [['status', 'create_at', 'member_id','source'], 'integer'],
            [['note', 'card_number', 'id_card'], 'string', 'max' => 255],
            [['id_card'], 'unique'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID自增',
            'member_id' => '会员编号',
            'document_type' => '1-身份证,2-居住证,3-签证,4-护照,5-户口本,6-军人证',
            'id_card' => '证件号',
            'status' => '1-提供的证件号与数据库中的一致;2-数据库中没有提供证件号;3-提供证件号与数据库中的不一致.',
            'card_number' => '用户提供的卡号',
            'create_at' => '创建时间',
            'note' => '备注',
            'source' => '1-手机APP-android;2-手机APP-ios; 3-小程序',
        ];
    }
}