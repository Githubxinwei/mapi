<?php

namespace common\models;

use common\models\relations\DealRelations;
use Yii;

class Deal extends \common\models\base\Deal
{
    use DealRelations;
}