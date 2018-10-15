<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 12:09
 * Desc:体侧信息
 */
namespace common\models;

use common\models\relations\MemberPhysicalTestRelations;

class MemberPhysicalTest extends \common\models\base\MemberPhysicalTest
{
    use MemberPhysicalTestRelations;

}