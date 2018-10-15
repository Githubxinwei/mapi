<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%mechanism}}".
 *
 * @property string $id
 * @property string $employee_id
 * @property string $city_id
 * @property string $name
 * @property string $address
 * @property string $create_at
 * @property string $domain
 * @property string $mechanism_code
 * @property string $legal_name
 * @property string $legal_mobile
 * @property string $legal_id_card
 * @property string $legal_id_card_pos
 * @property string $legal_id_card_oop
 * @property string $business_license
 * @property string $tax_prove
 * @property string $store_vi
 */
class Mechanism extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mechanism}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'city_id', 'create_at'], 'integer'],
            [['name', 'address', 'domain', 'mechanism_code', 'legal_name', 'legal_id_card'], 'string', 'max' => 200],
            [['legal_mobile', 'legal_id_card_pos', 'legal_id_card_oop', 'business_license', 'tax_prove', 'store_vi'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'employee_id' => '店长ID',
            'city_id' => '城市ID',
            'name' => '名称',
            'address' => '机构地址',
            'create_at' => '创建时间',
            'domain' => '域名',
            'mechanism_code' => '组织机构代码',
            'legal_name' => '法人代表',
            'legal_mobile' => '法人电话',
            'legal_id_card' => '法人身份证号码',
            'legal_id_card_pos' => '法人身份证正面',
            'legal_id_card_oop' => '法人身份证反面',
            'business_license' => '营业执照',
            'tax_prove' => '税务登记证明',
            'store_vi' => '营业执照',
        ];
    }
}
