<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25 0025
 * Time: 下午 5:59
 */

namespace common\models;


use common\models\relations\MemberCourseOrderRelations;

class MemberCourseOrder extends \common\models\base\MemberCourseOrder
{
    use MemberCourseOrderRelations;

}