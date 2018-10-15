<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%member_details}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $name
 * @property integer $sex
 * @property string $pic
 * @property string $id_card
 * @property string $birth_date
 * @property string $email
 * @property string $profession
 * @property string $family_address
 * @property string $hobby
 * @property string $month_income
 * @property string $way_to_shop
 * @property string $motto
 * @property string $created_at
 * @property string $updated_at
 * @property string $recommend_member_id
 * @property string $now_address
 * @property string $nickname
 * @property string $fingerprint
 * @property string $ic_number
 * @property string $note
 * @property integer $score
 * @property integer $document_type
 */
class MemberDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'sex', 'created_at', 'updated_at', 'recommend_member_id', 'score', 'document_type'], 'integer'],
            [['birth_date'], 'safe'],
            [['family_address', 'now_address', 'fingerprint'], 'string'],
            [['month_income'], 'number'],
            [['name', 'pic', 'id_card', 'email', 'hobby', 'way_to_shop', 'nickname', 'ic_number', 'note'], 'string', 'max' => 200],
            [['profession', 'motto','introduction'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键自增',
            'member_id' => '会员ID',
            'name' => '姓名',
            'sex' => '性别1:男2：女',
            'pic' => '头像',
            'id_card' => '身份证号',
            'birth_date' => '生日',
            'email' => '邮箱',
            'profession' => '职业',
            'family_address' => '家庭住址',
            'hobby' => '喜好',
            'month_income' => '月收入',
            'way_to_shop' => '来电途径',
            'motto' => '座右铭',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'recommend_member_id' => '推荐人id',
            'now_address' => '现居地',
            'nickname' => '会员昵称',
            'fingerprint' => '指纹',
            'ic_number' => '手环ic号',
            'note' => '备注',
            'score' => '积分',
            'document_type' => '证件类型:1身份证2居住证3签证4护照5户口本6军人证',
            'introduction' => '个人简介',
        ];
    }
}
