<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/19
 * Time: 15:42
 * Desc:会员信息
 */
namespace common\models;

use common\models\relations\MemberInformationRelations;

class MemberInformation extends \common\models\base\MemberInformation
{
    use MemberInformationRelations;
}