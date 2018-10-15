<?php
	
	namespace common\models;
	
	use common\models\relations\ActionRelations;
	use function GuzzleHttp\Psr7\str;
	use yii\base\InvalidParamException;
	
	class Action extends \common\models\base\Action
	{
		use ActionRelations;
	}