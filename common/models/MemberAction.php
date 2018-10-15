<?php

namespace common\models;

use common\models\Action;
use common\models\base\ActionImages;
use common\models\relations\TrainStagesRelations;
use yii\base\InvalidParamException;

class MemberAction extends \common\models\base\MemberAction
{
	public function getAction() {
		return $this->hasMany(Action::className(), ['id' => 'action_id']);
}
}