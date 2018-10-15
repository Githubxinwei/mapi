<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/8/2
 * Time: 10:18
 * Desc:会员购买私课订单详情扩展表
 */
namespace common\models;

use common\models\relations\MemberCourseOrderExtendRelations;

class MemberCourseOrderExtend extends \common\models\base\MemberCourseOrderExtend
{
    use MemberCourseOrderExtendRelations;

}