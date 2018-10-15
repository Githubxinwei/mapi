<?php

namespace common\models;

use Yii;
use \common\models\MemberCard;
use common\models\relations\MemberRelations;
class Member extends \common\models\base\Member
{
    use MemberRelations;
}