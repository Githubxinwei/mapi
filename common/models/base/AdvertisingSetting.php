<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/5/6
 * Time: 14:26
 */
namespace common\models\base;

use Yii;
class AdvertisingSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertising_setting}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type','status','company_id','create_at','create_id'], 'integer'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'type' => '类型 1启动页广告',
            'status' => '状态 0 不可用, 1 可用',
            'company_id' => '公司ID',
            'create_at' => '创建时间',
            'create_id' => '创建人ID',
        ];
    }
}