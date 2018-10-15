<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/11
 * Time: 11:21
 * Desc:会员健康餐预约记录
 */
namespace common\models;

use common\models\relations\MemberMealRecordRelations;

class MemberMealRecord extends \common\models\base\MemberMealRecord
{
    use MemberMealRecordRelations;
}