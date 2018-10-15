<?php
namespace backend\controllers;

use backend\models\Config;
use backend\models\Deal;
use backend\models\Member;
use backend\models\Organization;
use common\models\base\MemberBase;
use backend\modules\v1\models\SellCardForm;
use backend\models\RegistrationVenueForm;
use yii\web\Controller;
class PurchaseCardController extends Controller
{
    public $layout = false; //不使用布局
    public function actionStep()
    {
        return $this->render('/site/step');
    }
    public  function actionUser(){
        return $this->render('/site/user');
    }

}
