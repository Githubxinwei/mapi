<?php
namespace backend\models;
use Yii;
use common\models\Func;
use common\models\relations\CourseRelations;

class Deal extends \common\models\base\Deal
{
    public $sorts;
    public $venueId;
    public $searchContent;
    public $nowVenueId;
    public $nowCompanyId;
    public $nowBelongId;
    public $nowBelongType;
//    const SEARCH_CONTENT = 'searchContent';
//    const NOW_BELONG_ID = 'nowVenueId';
//    const NOW_BELONG_TYPE = 'nowCompanyId';
    const SEARCH_CONTENT = 'searchContent';
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';
    const NOW_COMPANY      = 'nowCompanyId';
    const NOW_VENUE        = 'nowVenueId';
    use CourseRelations;
    /**
     * 后台 - 合同管理 - 数据遍历
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param
     * @return object   //返回分页数据对象
     */
    public function getData($params)
    {
        $this->customLoad($params);
        $query = Deal::find()->alias('deal')->joinWith(["admin"])->joinWith(["dealType"])
            ->select("deal.*,cloud_admin.username,cloud_deal_type.type_name")
            ->orderBy($this->sorts)
            ->asArray();
        $query = $this->getSearchWhere($query);
        $data  = Func::getDataProvider($query, 8);
        return $data;
    }
    /**
     * 后台 - 合同管理 - 获取迈步合同名称(注意：迈步专用)
     * @author 黄华 <huanghua@itsports.club>
     * @create 2017/6/23
     */
    public static function getDealName()
    {
        return $organ   = Deal::find()->where(["name"=>"迈步会员卡协议"])->asArray()->one();
    }
    /**
     * 后台 - 合同管理 - 获取公司合同名称（所有公司通用）
     * @author 黄华 <huanghua@itsports.club>
     * @param  $companyId
     * @create 2017/6/23
     * @return array
     */
    public static function getDealNameByCompanyId($companyId)
    {
        return $organ   = Deal::find()->where(['like',"name","会员卡协议"])->andWhere(['company_id'=>$companyId])->asArray()->one();
    }
    /**
     * 后台 - 组织架构管理 - 执行搜索数据过滤
     * @create 2017/4/24
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query  //后台的sql语句
     * @return  mixed
     */
    public function getSearchWhere($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','deal.name', $this->searchContent],
            ['deal.deal_number'=>$this->searchContent]
        ]);
        $query->andFilterWhere(['deal.company_id'=>$this->nowCompanyId]);
        $query->andFilterWhere(['deal.venue_id'=>$this->nowVenueId]);
//        if($this->nowBelongType && $this->nowBelongType == 'company'){
//            $query->andFilterWhere(['deal.company_id'=>$this->nowBelongId]);
//        }
//        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
//            $query->andFilterWhere(['deal.company_id'=>$this->nowBelongId]);
//        }
        return $query;
    }

    /**
     * 后台 - 合同管理- 处理前台发送过来的搜索参数数据时
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/25
     * @param
     * @return object   //返回分页数据对象
     */
    public function customLoad($data)
    {
        $roleId             =   \Yii::$app->user->identity->level;
        if($roleId == 0 || $roleId == 29){
            $vId            =    Organization::find()->select('id')->where(['style'=>2])->asArray()->all();
            $venueIds       =    array_column($vId, 'id');
        }else{
            //拿到用户有权限查看的场馆
            $venuesId       =    Auth::findOne(['role_id' => $roleId])->venue_id;
            $authId         =    json_decode($venuesId);
            //去掉组织架构里面设置"不显示"的场馆id
            $venues         =    Organization::find()->where(['id'=>$authId])->select(['id','name'])->asArray()->all();
            $venueIds       =    array_column($venues, 'id');
        }
        $this->nowVenueId      = (isset($data[self::NOW_VENUE]) && !empty($data[self::NOW_VENUE])) ? $data[self::NOW_VENUE] : $venueIds;
//        $this->searchContent   = (isset($data[self::SEARCH_CONTENT]) && !empty($data[self::SEARCH_CONTENT])) ? $data[self::SEARCH_CONTENT] : null;
//        $this->nowVenueId      = (isset($data[self::NOW_VENUE]) && !empty($data[self::NOW_VENUE])) ? $data[self::NOW_VENUE] : null;
        $this->nowCompanyId    = (isset($data[self::NOW_COMPANY]) && !empty($data[self::NOW_COMPANY])) ? $data[self::NOW_COMPANY] : null;
        $this->searchContent   = (isset($data[self::SEARCH_CONTENT]) && !empty($data[self::SEARCH_CONTENT])) ? $data[self::SEARCH_CONTENT] : null;
        $this->nowBelongId     = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID])) ? $data[self::NOW_BELONG_ID] : null;
        $this->nowBelongType   = (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE])) ? $data[self::NOW_BELONG_TYPE] : null;
        $this->sorts           = self::loadSort($data);
        return true;
    }
    /**
     * 后台 - 合同管理- 对各个字段的排序
     * @create 2017/4/25
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = ['deal.create_at' => SORT_DESC];
        if(!isset($data['sortType'])){ return $sorts;}
        switch ($data['sortType']){
            case 'dealTypeName'  :
                $attr = '`cloud_deal_type`.type_name';
                break;
            case 'dealName'  :
                $attr = '`deal`.name';
                break;
            case 'dealNumber':
                $attr = '`deal`.deal_number';
                break;
            case 'create_at':
                $attr = '`deal`.create_at';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];
        }
        return $sorts;
    }
    /**
     * 后台 - 合同管理- 获取最终排序规则
     * @create 2017/4/25
     * @author houkaixin<houkaixin@itsports.club>
     * @param $sort     // 前台传过来的排序规则（ASC，DES两种情况）
     * @return string
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
     * 后台 - 合同管理 - 删除指定数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/25
     * @param $id
     * @return boolean   //返回删除数据的结果
     */
    public function getDel($id){
        $result = Deal::findOne($id);
        $delResult = $result->delete();
        if ($delResult) {
            return true;
        } else {
            return false;
        }
    }
    public static function getDealOne($id)
    {
        return Deal::find()
            ->joinWith(['dealType dealType'])
            ->where(['cloud_deal.id'=>$id])->asArray()->one();
    }

    /**
     * 后台 - 合同类型管理  查询所有合同
     * @author HuangPengju <HuangPengju@itsports.club>
     * @create 2017/4/27
     * @return array|\yii\db\ActiveRecord[] //查询结果
     * @update huangpengju
     * @update 2017/06/10
     * @param $id       //公司或者场馆id
     * @param $type     //类型
     */
    public function getDealInfo($dealType,$id,$type)
    {
        if($id == 1 || $id == 75){
            $id = [1,75];
        }else{
            $id = [$id];
        }
        $data = Deal::find()->alias('deal')->where(['type' => $dealType])->select('id,name')->asArray();
        if(isset($type) && $type == 'company'){
            $data->andFilterWhere(['in','deal.company_id',$id]);
        }
        if(isset($type) && $type == 'venue'){
            $data->andFilterWhere(['in','deal.company_id',$id]);
        }
        $data = $data->all();
        return $data;
    }
}