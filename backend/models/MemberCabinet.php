<?php
namespace backend\models;
use common\models\base\Organization;
use common\models\relations\MemberCabinetRelations;
use common\models\Func;

class MemberCabinet extends \common\models\MemberCabinet
{
    use MemberCabinetRelations;
    public $nowBelongId;
    public $nowBelongType;
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    public $cabinetTypeId;
    const CABINET_TYPE_ID = 'cabinetTypeId';
    public $day;
    const DAY = 'day';
    public $venueId;
    const VENUE_ID = 'venueId';
    public $startTime;
    public $endTime;
    /**
     * 后台会员管理 - 会员详细信息查询 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/6
     * @param id
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCabinetData($id,$venueId)
    {

        $model = MemberCabinet::find()
            ->alias('mc')
            ->joinWith(['member mm'])
            ->joinWith(['employee employee'])
            ->joinWith(['cabinet cabinet'])
            ->joinWith([
                'cabinet cabinet'=>function($query){
                    $query->joinWith(['cabinetType cabinetType']);
                }
            ])
            ->where(['mc.member_id'=>$id])
            ->andFilterWhere(['cabinet.venue_id'=>$venueId])
            ->orderBy(['mc.id' =>SORT_DESC])
            ->asArray();
        $data              =  Func::getDataProvider($model,8);
//        $data->models      =  $this->getCabinetTypename($data->models);
        return $data;
    }

    /**
     * 后台会员管理 - 柜子信息查询 - 获取柜子类型表类型名称
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/20
     * @return string
     */
    public function getCabinetTypename($data)
    {

        foreach ($data as &$value){
            $value['cabinetType'] = Cabinet ::find()->alias('c')
                ->select('cloud_cabinet_type.*,c.cabinet_type_id')
                ->joinWith(['cabinetType'])
                ->where(['c.cabinet_type_id'=>$value['id']])->asArray()->one();
        }
        return $data;
    }
    /**
     * 后台 - 会员管理 - 会员柜表删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/18
     * @return bool
     */
    public  function  getMemberCabinetDel($id)
    {
        $memberCabinet            =   MemberCabinet::findOne(['id'=>$id]);
        $resultDel                =   $memberCabinet->delete();

        if($resultDel)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台 - 会员管理 - 会员柜表删除
     * @author Houkaixin<Houkaixin@itsports.club>
     * @create 2017/5/3
     * @param $param //数组 会员柜子id，柜子id
     * @return bool
     */
   public function getDel($param){
       $object      = MemberCabinet::findOne($param["id"]);
       $delResult   =  $object->delete();
       //修改原有柜子的状态
       $model = Cabinet::findOne(["id"=>$param["cabinetId"]]);
       $model->status = 1;
       $result = $model->save();
       if($delResult&&$result){
           return true;
       }else{
           return false;
       }
   }

    /**
     * @云运动 - API - 获取会员所有柜子信息（ios调用）
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $accountId         //账户id
     * @return array|\yii\db\ActiveRecord[] 
     */
    public function getMemberCabinetAll($accountId)
    {
        $members = Member::find()->where(['member_account_id' => $accountId])->asArray()->all();
        foreach ($members as $member) {
            $ids[] = $member['id'];
        }
        $cabinet = MemberCabinet::find()
                   ->alias('mc')
                   ->joinWith(['cabinet cabinet'=>function($query){
                       $query->joinWith(["organization as o"],false);
                       $query->select("o.name as venue_name");
                   }],false)
                   ->where(['mc.member_id'=>$ids])
                   ->select('
                            mc.id as memberCabinetId,
                            mc.cabinet_id,
                            mc.status,
                            cabinet.cabinet_number as cabinetNum,
                            cabinet.cabinet_model as cabinetModel,
                            o.name as venue_name
                            ')
                   ->asArray()
                   ->all();
        foreach ($cabinet  as $k=>&$v)
        {
            if($v['cabinetModel'] == 1)
            {
                $v['cabinetModel'] = '大柜';
            }else if($v['cabinetModel'] == 2)
            {
                $v['cabinetModel'] = '中柜';
            }else if($v['cabinetModel'] == 3)
            {
                $v['cabinetModel'] = '小柜';
            }
        }
        return $cabinet;
    }
    /**
     * @云运动 - API - 获取会员柜子详细信息（ios调用）
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/27
     * @param $memberCabinetId   //会员柜子id
     * @param $memberId         //会员id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMemberCabinetInfo($memberCabinetId,$memberId)
    {
        $cabinet = MemberCabinet::find()
            ->alias('mc')
            ->joinWith(['cabinet cabinet'],false)
            ->where(['mc.id'=>intval($memberCabinetId)])
            ->select('
                    mc.id as memberCabinetId,
                    mc.cabinet_id,
                    mc.price,
                    mc.start_rent,
                    mc.end_rent,
                    mc.status,
                    cabinet.deposit as deposit,
                    cabinet.cabinet_number as cabinetNum,
                    cabinet.cabinet_model as cabinetModel,
                    ')
            ->asArray()
            ->one();
        if(!empty($cabinet)){
            if($cabinet['cabinetModel'] == 1)
            {
                $cabinet['cabinetModel'] = '大柜';
            }else if($cabinet['cabinetModel'] == 2)
            {
                $cabinet['cabinetModel'] = '中柜';
            }else if($cabinet['cabinetModel'] == 3)
            {
                $cabinet['cabinetModel'] = '小柜';
            }
            $cabinet['cabinetDay'] = (int)(($cabinet['end_rent'] - $cabinet['start_rent'])/86400);    //租用天数
            $cabinet['start_rent'] = date('Y-m-d',$cabinet['start_rent']);                          //租用日期
            $cabinet['end_rent']   = date('Y-m-d',$cabinet['end_rent']);                            //到期时间
            $cabinet['deposit']    = !empty($cabinet['deposit'])?$cabinet['deposit']:0;                                                            //押金50先写死
        }
        return $cabinet;
    }

    /**
     * 后台更柜管理 - 会员即将到期 - 多表查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/12/13
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public  function  memberCabinetDue($params)
    {
        $this->customLoad($params);
        $query = MemberCabinet::find()
            ->alias('mc')
            ->joinWith(['member member'],false)
            ->joinWith([
                'member member'=>function($query){
                    $query->joinWith(['memberDetails memberDetails'],false);
                }
            ],false)
            ->joinWith(['cabinet cabinet'],false)
            ->joinWith([
                'cabinet cabinet'=>function($query){
                    $query->joinWith(['cabinetType cabinetType'],false);
                }
            ],false)
            ->where(['>=','mc.end_rent',time()])
            ->select("mc.id,mc.end_rent,cabinetType.type_name,cabinet.cabinet_number,cabinet.cabinet_model,memberDetails.name,member.mobile")
            ->groupBy(['mc.id'])
            ->orderBy(['mc.id' =>SORT_DESC])
            ->asArray();
        $model  = $this->getSearchWhere($query);
        $data   =  Func::getDataProvider($model,8);
        return $data;
    }

    /**
     * 更柜管理 - 会员即将到期 - 搜索数据处理数据
     * @create 2017/12/13
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoad($data)
    {
        $cardObj     = new \backend\models\CardCategory();
        $venueIds    = $cardObj->getVenueIdByRole();
        $this->venueId = (isset($params[self::VENUE_ID]) && !empty($params[self::VENUE_ID])) ? $params[self::VENUE_ID] : $venueIds;
        $this->nowBelongId  = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType= (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->day          = (isset($data[self::DAY]) && !empty($data[self::DAY])) ? $data[self::DAY] : null;
        $this->cabinetTypeId= (isset($data[self::CABINET_TYPE_ID]) && !empty($data[self::CABINET_TYPE_ID])) ? $data[self::CABINET_TYPE_ID] : null;
        if(isset($data['day']) && $this->day == 'd'){
            $this->startTime = strtotime(Func::getGroupClassDate($this->day,true));
            $this->endTime   = strtotime(Func::getGroupClassDate($this->day,false));
        }elseif(isset($data['day']) && $this->day == 'w'){
            $this->startTime = strtotime(Func::getGroupClassDate($this->day,true));
            $this->endTime   = strtotime(Func::getGroupClassDate($this->day,false));
        }elseif(isset($data['day']) && $this->day == 'm'){
            $this->startTime = strtotime(Func::getGroupClassDate($this->day,true));
            $this->endTime   = strtotime(Func::getGroupClassDate($this->day,false));
        }
        return true;
    }
    /**
     * 更柜管理 - 会员即将到期 - 增加搜索条件
     * @create 2017/12/13
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere([
            'and',
            [
                'cabinetType.id' => $this->cabinetTypeId,
            ],
        ]);
        if(!empty($this->day)){
            $query->andWhere(['between','mc.end_rent',$this->startTime,$this->endTime]);
        }
        $query->andFilterWhere(['member.venue_id'=>$this->venueId]);
        return $query;
    }

    /**
     * 云运动 - 更柜 - 发送柜子到期时间
     * @author huanghua <zhumengke@itsports.club>
     * @create 2017/11/27
     * @param
     * @return array
     */
    public function sendCabinet($post)
    {
        $this->customLoad($post);
        $query = MemberCabinet::find()
            ->alias('mc')
            ->joinWith(['member member'],false)
            ->joinWith([
                'member member'=>function($query){
                    $query->joinWith(['memberDetails memberDetails'],false);
                }
            ],false)
            ->joinWith(['cabinet cabinet'],false)
            ->joinWith([
                'cabinet cabinet'=>function($query){
                    $query->joinWith(['cabinetType cabinetType'],false);
                }
            ],false)
            ->where(['>=','mc.end_rent',time()])
            ->select("mc.id,mc.end_rent,cabinetType.type_name,cabinet.cabinet_number,cabinet.cabinet_model,memberDetails.name,member.mobile")
            ->groupBy(['mc.id'])
            ->orderBy(['mc.id' =>SORT_DESC])
            ->asArray();
        $query    = $this->getSearchWhere($query);
        $memberCabinetId = array_column($query->all(),'id');
        if(!empty($memberCabinetId)){
            foreach ($memberCabinetId as $key=>$value) {
                $this->sendCabinetOne($value);
            }
        }else{
            return false;
        }

    }

    /**
     * 云运动 - 更柜管理 - 发送到期提醒短信（单发）
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/12/15
     * @param $memberCabinetId
     * @return array
     */
    public function sendCabinetOne($memberCabinetId)
    {
        $query = MemberCabinet::find()
            ->alias('mc')
            ->joinWith(['member member'],false)
            ->joinWith([
                'member member'=>function($query){
                    $query->joinWith(['memberDetails memberDetails'],false);
                }
            ],false)
            ->joinWith(['cabinet cabinet'],false)
            ->joinWith([
                'cabinet cabinet'=>function($query){
                    $query->joinWith(['cabinetType cabinetType'],false);
                }
            ],false)
            ->where(['>=','mc.end_rent',time()])
            ->andWhere(['mc.id'=>$memberCabinetId])
            ->select("mc.id,mc.end_rent,cabinetType.type_name,cabinet.cabinet_number,cabinet.cabinet_model,memberDetails.name,member.mobile")
            ->asArray()->one();
            if($query['mobile'] == '0' || empty($query['mobile'])){
                return '手机号不存在，无法发送';
            }else{
                Func::sendCabinetData($query['mobile'],$query['name'],$query['type_name'],$query['cabinet_number'],$query['end_rent']);
            }
    }
}