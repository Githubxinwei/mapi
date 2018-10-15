<?php
namespace backend\models;
use common\models\Func;
use common\models\relations\ConfigRelations;
class Config extends \common\models\Config
{
    use ConfigRelations;
    public $searchDateStart;//搜索开始时间
    public $searchDateEnd;//搜索结束时间
    public $nowBelongId;
    public $nowBelongType;
    public $venueId;
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    /**
     * 后台会员管理 - 预约设置查询 - 数据库数据
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/4/14
     * @return \yii\db\ActiveQuery
     */

    public function configWay()
    {
        $query = Config::find()
            ->alias('c')
            ->where(['type'=>'private'])
            ->asArray()->all();

        return $query;
    }
    /**
     * 潜在会员 - 来源配置 - 查询
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/5
     * @param $id
     * @param $type
     * @return \yii\db\ActiveQuery
     */
    public function getMemberConfig($id,$type,$configType = 'member')
    {

        $query = Config::find()
            ->where(['type'=>$configType])
            ->asArray();
        if(isset($type) && $type == 'company'){
            $query = $query->andFilterWhere(['cloud_config.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $query = $query->andFilterWhere(['cloud_config.venue_id'=>$id]);
        }
        $query = $query->all();
        return $query;
    }
    /**
     * 潜在会员 - 来源渠道 - 查询
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/7/14
     * @param $companyId
     * @param $venueId
     * @param $type
     * @return \yii\db\ActiveQuery
     */
    public function getMemberConfigData($companyId,$venueId,$type = 'member')
    {
        if($type == 'card'){
            $query = Config::find()
                ->where(['type'=>$type])
                ->andWhere(['venue_id'=>$venueId])
                ->asArray();
        }else{
            $query = Config::find()
                ->where(['type'=>$type])
                ->andWhere(['company_id'=>$companyId])
                ->andWhere(['venue_id'=>$venueId])
                ->asArray();
        }
        $query = $query->all();
        return $query;
    }

    /**
     * 后台 - 会员管理 - 会员基本信息删除（范围：会员详细信息和会员卡）
     * @author Hou kaixin houkaixin@itsports.club
     * @create 2017/3/31
     * @param $id
     * @return bool
     */
    public function getConfigDate($id)
    {
        $config = Config::findOne($id);
        $deleteConfig = $config->delete();
        if($deleteConfig)
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 销售主页 - 客户渠道来源
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/27
     * @param $params
     * @param $type
     * @return array
     */
    public function actionSourceMember($type,$params)
    {
        $this->customMember($params);
        $this->getDateWhere($type);
        return $this->getSourceMemberData($this->searchDateStart,$this->searchDateEnd);
    }
    /**
     * 销售主页 - 客户渠道来源 - 搜索数据处理数据
     * @create 2017/7/27
     * @author 黄华 <huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customMember($data)
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds       =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId         =    json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues         =    Organization::find()->where(['id'=>$authId])->andWhere(['is_allowed_join'=>1])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        $this->venueId       = (isset($data['venueId']) && !empty($data['venueId'])) ? $data['venueId'] : $venueIds;
//        $this->nowBelongId   = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
//        $this->nowBelongType = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        return true;
    }

    /**
     * 销售主页 - 销售额
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getSourceMemberData($beginDate,$endDate)
    {
        $query = Config::find()
            ->alias('cc')
            ->where(['key'=>'source'])
            ->andWhere(['type'=>'member'])
            ->select('cc.id,cc.value')
            ->asArray();

        $query   = $this->setWheres($query);
        $queryId = array_column($query,'id');
        $count   = [];
        foreach($queryId as $value){
            $member = Member::find()
                ->alias('mm')
                ->joinWith(['memberDetails memberDetails'])
                ->where(['memberDetails.way_to_shop' => $value])
                ->asArray();

            $config = Config::findOne(['id'=>$value]);
            $memberDetail = MemberDetails::find() ->joinWith(['member member'])->where(['way_to_shop'=>$config['id']])->andWhere(['between','member.register_time',strtotime($beginDate),strtotime($endDate)])->count();
            $member   = $this->setWhere($member);
            if(!empty($member)){
                $count[] = ['value'=>$config['value'],'totalSum'=>$memberDetail];
            }
        }
        return $count;



    }
    /**
     * 后台 - 约课管理 - 处理搜索条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $query
     * @return string
     */
    public function setWheres($query)
    {
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['cc.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['cc.venue_id'=>$this->nowBelongId]);
//        }
        $query->andFilterWhere(['cc.venue_id'=>$this->venueId]);
        return $query->all();
    }
    /**
     * 后台 - 约课管理 - 处理搜索条件
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/5/2
     * @param $query
     * @return string
     */
    public function setWhere($query)
    {
        if($this->nowBelongType && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['mm.company_id'=>$this->nowBelongId]);
        }
        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
            $query->andFilterWhere(['mm.venue_id'=>$this->nowBelongId]);
        }
        return $query->count();
    }
    /**
     * 后台 - 销售主页 - 处理搜索条件
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/7/21
     * @param $attr
     * @return string
     */
    public function getDateWhere($attr)
    {
        if($attr == 'w'){
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }elseif($attr == 'd'){
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }else{
            $this->searchDateStart = Func::getGroupClassDate($attr,true);
            $this->searchDateEnd   = Func::getGroupClassDate($attr,false);
        }
    }
    /**
     * 后台 - 会员管理 - 会员基本信息删除（范围：会员详细信息和会员卡）
     * @author Hou kaixin houkaixin@itsports.club
     * @create 2017/3/31
     * @return bool
     */
    public function getConfigCard()
    {
//        $params = \Yii::$app->request->queryParams;
//        if(isset($params['venueId'])){
//            $venueId = $params['venueId'];
//        }else{
//            $venueId = $this->venueId;
//        }
//        return Config::find()->filterWhere(['type' => $params['type'],'venue_id' => $venueId])->andFilterWhere(['or',['key' => $params['key']],['key' => $params['beforeRenew']]])->asArray()->all();
        $params = \Yii::$app->request->queryParams;
        if(isset($params['venueId'])){
            $venueId = $params['venueId'];
        }else{
            $venueId = $this->venueId;
        }
        return Config::find()->filterWhere(['type' => $params['type'],'key' => $params['key'],'venue_id' => $venueId])->asArray()->one();
    }
    /**
     * 后台 - 会员管理 - 续费卡种设置获取
     * @author Huanghua huanghua@itsports.club
     * @param $venueId
     * @create 2017/9/26
     * @return bool
     */
    public function getConfigCardData($venueId)
    {
        $params = \Yii::$app->request->queryParams;
        return Config::find()->filterWhere(['type' => $params['type'],'key' => $params['beforeRenew'],'venue_id' => $venueId])->asArray()->one();
    }
    /**
     * 后台 - 会员管理 - 续费卡种设置获取
     * @author Huanghua huanghua@itsports.club
     * @param $appType // 请求手机端类型
     * @param $venue   // 请求场馆名称
     * @create 2017/9/26
     * @return integer  1代表 必须更新 2代表不必须更新
     */
    public function gainAppConfig($appType,$venue){
        $config = \common\models\base\Config::find()
                                    ->where(["and",["type"=>$venue],["key"=>$appType]])
                                    ->one();
        if(empty($config)){
           return 2;
        }
        if(!isset($config->value)){
           return 2;
        }
        return $config->value;
    }
    public function splitQuitCabinetValue($companyId,$venueId)
    {
        $setCost = $this->getQuitCabinetValue($companyId,$venueId,'setCost');
        $dateType = $this->getQuitCabinetValue($companyId,$venueId,'dateType');
        $setDays = $this->getQuitCabinetValue($companyId,$venueId,'setDays');
        $data = [];
        $data['setCost'] = $setCost;
        $data['dateType'] = $dateType;
        $data['setDays'] = $setDays;
        return $data;
    }

    /**
     * @desc: 获取更柜管理退柜的配置数据
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/30
     * @param $companyId
     * @param $venueId
     * @param $key
     * @return mixed
     */
    private function getQuitCabinetValue($companyId,$venueId,$key)
    {
        $config = \common\models\base\Config::find()
            ->where(['type'=>'quitCabinet'])
            ->andWhere(['company_id'=>$companyId])
            ->andWhere(['venue_id'=>$venueId])
            ->andWhere(['key'=>$key])->one();
        return isset($config->value) ? $config->value : null;
    }
}