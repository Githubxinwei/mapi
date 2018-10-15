<?php

namespace common\models;

use Yii;
use  common\models\relations\MemberCabinetRelations;
class MemberCabinet extends \common\models\base\MemberCabinet
{
   use MemberCabinetRelations;
}