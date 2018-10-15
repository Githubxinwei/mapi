<?php
namespace common\models\relations;
use common\models\FeedbackType;


trait FeedbackRelations
{
    public function getFeedbackType()
    {
        return $this->hasOne(FeedbackType::className(), ['id' => 'type_id']);
    }
}
