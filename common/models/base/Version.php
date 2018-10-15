<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%version}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $version
 * @property string $url
 * @property integer $must
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%version}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['must'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['version'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'name' => '产品代号[android_client->安卓会员端,ios_business->IOS管理端,ios_coach->IOS私教端,ios_group->IOS团教端]',
            'version' => '最新版本号',
            'url' => '安装文件URL',
            'must' => '0不必须更新1必须更新',
        ];
    }
}
