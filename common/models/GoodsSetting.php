<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/9
 * Time: 18:35
 * Desc:商品设置表
 */
namespace common\models;

use common\models\relations\GoodsSettingRelations;

class GoodsSetting extends \common\models\base\GoodsSetting
{
    use GoodsSettingRelations;
}