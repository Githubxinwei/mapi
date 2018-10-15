<?php

namespace common\models;

use common\models\relations\FeedbackRelations;
use yii\behaviors\TimestampBehavior;

class Feedback extends \common\models\base\Feedback
{
    use FeedbackRelations;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}