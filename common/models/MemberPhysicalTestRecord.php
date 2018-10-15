<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 15:06
 * Desc:体侧信息预约记录
 */
namespace common\models;

use common\models\relations\MemberPhysicalTestRecordRelations;

class MemberPhysicalTestRecord extends \common\models\base\MemberPhysicalTestRecord
{
    use MemberPhysicalTestRecordRelations;

}