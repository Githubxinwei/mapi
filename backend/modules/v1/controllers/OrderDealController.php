<?php
namespace backend\modules\v1\controllers;

use Yii;
use backend\modules\v1\models\OrderDeal;
use backend\modules\v1\models\Member;
use yii\data\ActiveDataProvider;

class OrderDealController extends BaseController
{
    public $modelClass = 'backend\modules\v1\models\OrderDeal';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $accountId = Yii::$app->request->get('accountId', 0);
        if(empty($accountId)) return $this->error('参数缺失');
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        $ids = [];
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $query = OrderDeal::find()->alias('o')
            ->joinWith(['memberCard mc'=>function($q){
                $q->andOnCondition(['o.consumption_type'=>'card']);
            }], FALSE)->joinWith(['memberCourseOrder mco'=>function($q){
                $q->andOnCondition(['o.consumption_type'=>['charge', 'chargeGroup']])->joinWith('chargeClass cc');
            }], FALSE)
            ->where(['o.member_id'=>$ids, 'o.status'=>2])->andWhere(['or', ['>', 'mc.deal_id', 0], ['>', 'cc.deal_id', 0]]);

        $query->orderBy('id desc');
        return new ActiveDataProvider(['query' => $query]);
    }

}
