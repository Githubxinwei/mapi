<?php
namespace backend\models;
use common\models\relations\ConsumptionHistoryRelations;
use common\models\Func;
class ConsumptionHistory extends \common\models\base\ConsumptionHistory
{
    use ConsumptionHistoryRelations;
    public $sorts;
    public $venueId;
    /**
     * 后台 - 私教课程管理 - 获取会员办理私教消费记录
     * @return string
     * @author 李慧恩
     * @create 2017-4-26
     * @update huangpengju
     * @update 2017/5/27
     * @param
     * @param
     */
    public function getChargeMemberHistory($memberId,$classId)
    {
        $data = ConsumptionHistory::find()
            ->alias('ch')
            ->joinWith(['employee employee'])
            ->joinWith(['memberOrderDetails details'])
            ->where(['ch.consumption_type'=>'charge'])
            ->andWhere(['ch.consumption_type_id'=>$classId])
            ->andWhere(['ch.member_id'=>$memberId])
            ->asArray()->all();
        return $data;
    }

    /**
     * 云运动 - 会员管理 - 查询会员的缴费记录
     * @author 朱梦珂<zhumengke@itsports.club>
     * @create 2017/5/23
     * @return array
     */
    public function getHistory($id)
    {
        $history = ConsumptionHistory::find()
            ->alias('ch')
            ->joinWith(['memberCard mc'])
            ->joinWith(['employee ee'])
            ->select(
                'ch.id,
                ch.consumption_type_id,
                ch.seller_id,
                ch.consumption_date,
                ch.consumption_type,
                ch.consumption_amount,
                ch.category,
                ch.describe,
                ch.due_date,
                ch.remarks,
                ch.payment_name,
                ch.activate_date,
                mc.card_name,
                mc.invalid_time,
                mc.member_id,
                mc.active_time,
                ee.name')
            ->where(['ch.consumption_type_id' => $id])
            ->andWhere('ch.member_id=mc.member_id')
            ->asArray()
            ->all();
        return $history;
    }

    /**
     * 会员管理 - 会员卡详情 - 批量删除续费记录
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/12/09
     */
    public function delRenewHistory($data)
    {
        $idArr = $data['conHistoryId'];
        return ConsumptionHistory::deleteAll(['id' => $idArr]);
    }
    /**
     * 云运动 - 新柜子管理 - 退柜消费数录入
     * @author 侯凯新<houkaixin@itsports.club>
     * @create 2017/6/9
     * $param $data   //需要录入的数据
     * @return array
     */
    public function cabinetDataInsertHistory($data){
        $model = new ConsumptionHistory();
        $model->member_id            = $data["memberId"];
        $model->consumption_type    = $data["consumeStatus"];
        $model->consumption_type_id = $data["consumeTypeId"];     //当时租赁柜子的id
        $model->type                  = 1;                            //现金消费
        $model->consumption_date    = time();
        $model->consumption_amount  = $data["price"];
        $model->consumption_time    = time();
        $model->consumption_times   = 1;
        $model->cash_payment         = $data["price"];
        $model->venue_id              = $data["venueId"];
        $model->describe              = json_encode($data["consumeStatus"]);
        $model->company_id           = $data["companyId"];
        $model->due_date             = isset($data['due_date']) ? $data['due_date'] : null;
        $model->cashier_order        = isset($data["cashOrder"])&&!empty($data["cashOrder"])?$data["cashOrder"]:null;
        $model->category              = $data["status"];
        $model->consume_describe     = $data["consumeDescribe"];
        if($model->save()){
            return "success";
        }else{
            return $model->errors;
        }
    }

    /**
     *后台会员管理 - 会员详细信息 -  消费记录查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/19
     * @param @id
     * @return bool|string
     */
    public function consumptionHistoryData($params)
    {
        $this->customLoad($params);
        $model = ConsumptionHistory::find()
            ->alias('ch')
            ->joinWith(['memberCard memberCard'])
            ->where(['ch.member_id' => $params['memberId']])
            ->andFilterWhere(['ch.venue_id' => $this->venueId])
            ->orderBy($this->sorts)
            ->asArray();
        $dataProvider = Func::getDataProvider($model,10);

        return $dataProvider;
    }

    /**
     * 会员卡管理 - 会员卡 - 搜索数据处理数据
     * @create 2017/4/7
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
    {

        $card               =  new \backend\models\CardCategory();
        $this->venueId      =  $card->getVenueIdByRole();
        $this->sorts = self::loadSort($data);

        return true;
    }

    /**
     * 会员卡管理 - 会员信息管理 - 获取排序条件
     * @create 2017/7/20
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return mixed
     */
    public static function loadSort($data)
    {
        $sorts = [
            'id' => SORT_DESC
        ];
        if (!isset($data['sortType'])) {
            return $sorts;
        }
        switch($data['sortType'])
        {
            case 'member_consumptionDate'          :
                $attr = '`ch`.consumption_date';
                break;
            case 'member_memberType'           :
                $attr = '`ch`.type';
                break;
            case 'member_consumptionAmount'           :
                $attr = '`ch`.consumption_amount';
                break;
            case 'member_memberCardBalance'        :
                $attr = '`memberCard`.balance';
                break;
            case 'member_consumptionType'   :
                $attr = '`ch`.consumption_type';
                break;
            default;
                return $sorts;
        }
        return $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];

    }


    /**
     * 会员卡管理 - 会员信息管理 - 获取搜索规格
     * @create 2017/7/20
     * @author huanghua<huanghua@itsports.club>
     * @param $sort
     * @return mixed
     */
    public static function convertSortValue($sort)
    {
        if ($sort == 'ASC') {
            return SORT_ASC;
        } elseif ($sort == 'DES') {
            return SORT_DESC;
        }
    }

    /**
     * 后台 - 会员管理 - 会员消费记录删除
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/16
     * @param $consumptionId
     * @return bool
     */
    public  function  getConsumptionDel($consumptionId)
    {
        $consumptionData    =   \common\models\base\ConsumptionHistory::findOne($consumptionId);
        $resultDelMem       =   $consumptionData->delete();
        if($resultDelMem)
        {
            return true;
        }else{
            return false;
        }
    }

    public function getMemberChangeRecords($memberId)
    {
        $query = ConsumptionHistory::find()
            ->alias('ch')
            ->joinWith(['memberCard mc'],false)
            ->joinWith(['employee ee'],false)
            ->select("
            ch.id,
            ch.consumption_date,
            ch.consumption_amount,
            ch.payment_name,
            ch.consumption_type_id,
            ch.category,
            mc.card_number as card_number,
            ee.name as employeeName,
            ch.seller_id,
            mc.card_name,
            ")
            ->orderBy(['ch.consumption_date'=>SORT_DESC])
            ->where(
                ['and',
                ['ch.member_id'=>$memberId],
                ['ch.consumption_type'=>['card','cardRenew']]
            ])
            ->asArray()->all();
        /*$dataProvider = Func::getDataProvider($query,3);
        return $dataProvider;*/
        return $query;
    }
}