<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%scan_code_record}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $create_at
 * @property string $member_card_id
 * @property integer $identify
 */
class ScanCodeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scan_code_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'create_at', 'member_card_id', 'identify'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'member_id' => '会员id',
            'create_at' => '创建时间',
            'member_card_id' => '会员卡id',
            'identify' => '1:会员 2:员工',
        ];
    }
}
