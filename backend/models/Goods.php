<?php
namespace backend\models;
use common\models\Func;
use common\models\relations\GoodsRelations;
class Goods extends \common\models\base\Goods
{
     use GoodsRelations;
     public $topSearch;
     public $sorts;

    public $keywords;//关键字仓库名和商品名
    public $venueId;//场馆id
    public $startTime;//开始时间
    public $endTime;//结束时间
    public $type;//类型
    public $category;//品类
//    public $goodsBrands;//商品品牌
//    public $goodsModel;//商品型号
    const KEY = 'keywords';
    const VENUE_ID = 'venueId';
    const START = 'startTime';
    const END = 'endTime';
    const TYPE = 'type';
    const CATEGORY = 'category';
//    const GOODS_BRANDS = 'goodsBrands';
//    const GOODS_MODEL = 'goodsModel';

    public $nowBelongId;
    public $nowBelongType;
    const NOW_BELONG_ID = 'nowBelongId';
    const NOW_BELONG_TYPE = 'nowBelongType';




     public function getData($params,$id,$type){
       $this->customLoad($params);
       $query = Goods::find()->joinWith(["goodsType","goodsDetail",
           "goodsChange"],false)
              ->select("   cloud_goods.id,
                        cloud_goods.goods_attribute,
                        cloud_goods.goods_type_id,  
                        cloud_goods.goods_brand as goodsBrand,
                        cloud_goods.goods_name as goodsName,
                        cloud_goods.unit_price as unitPrice,
                        cloud_goods.unit,
                        cloud_goods.goods_model as goodsModel,
                        cloud_goods.goods_producer as goodsProducer,
                        cloud_goods.goods_supplier as goodsSupplier,
                        cloud_goods_detail.storage_num,                     
                        cloud_goods_type.goods_type,
                        cloud_goods_change.list_num as goodsListNum,
                        cloud_goods_change.status,
                        sum(cloud_goods_change.operation_num) as intoNum,                     
                        cloud_goods_change.describe,                
                        ")
              ->orderBy($this->sorts)
              ->groupBy("cloud_goods.id")
              ->asArray();
       $query = $this->getSearchWhere($query,$id,$type);
       $data  = Func::getDataProvider($query,8);
       return $data;
   }
    /**
     * 后台 - 新商品管理 - 处理前台发送过来的数据
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/4/24
     * @param
     * @return boolean   //返回结果对象
     */
    public function customLoad($data)
    {
        $this->topSearch   = (isset($data["topSearch"]) && !empty($data["topSearch"]))?$data["topSearch"]: null;
        $this->sorts       = self::loadSort($data);
        return true;
    }
    /**
     * 后台 - 组织架构管理 - 对各个字段的排序
     * @create 2017/4/24
     * @author houkaixin<houkaixin@itsports.club>
     * @param $data  array //前台获取的排序处理数据
     * @return array
     */
    public static function loadSort($data)
    {
        $sorts = ['cloud_goods.create_time'=> SORT_DESC];
        if(!isset($data["sortType"])){ return $sorts;}
        switch ($data["sortType"]){
            case 'goodsName':    //商品名称
                $attr = 'cloud_goods.goods_name';
                break;
            case 'goodsType':   //商品类别
                $attr = 'cloud_goods_type.goods_type';
                break;
            case 'goodsBrand':  //商品品牌
                $attr = 'cloud_goods.goods_brand';
                break;
            case 'unitPrice':   //商品单价
                $attr = 'cloud_goods.unit_price';
                break;
            case 'intoNum':     //入库数量
                $attr = 'intoNum';
                break;
            case "storeNum" :  //结库余存
                $attr = 'cloud_goods_detail.storage_num';
                break;
            case "goodsAttribute":  // 商品 属性
                $attr = 'cloud_goods.goods_attribute';
                break;
            default:
                $attr = NULL;
        };
        if($attr){
            $sorts = [$attr=>self::convertSortValue($data['sortName'])];
        }
        return $sorts;
    }
    /**
     * 后台 - 新商品管理 - 结库余存
     * @create 2017/6/6
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
     * 后台 - 新商品管理- 执行搜索数据过滤
     * @create 2017/6/6
     * @author houkaixin<houkaixin@itsports.club>
     * @param  $query
     * @param  $id
     * @param  $type
     */
    public function getSearchWhere($query,$id,$type)
    {
        $query->andFilterWhere([
            'like','cloud_goods.goods_name',$this->topSearch
        ]);
        if(isset($type) && $type == 'company'){
            $query = $query->andFilterWhere(['cloud_goods.company_id'=>$id]);
        }
        if(isset($type) && $type == 'venue'){
            $query = $query->andFilterWhere(['cloud_goods.venue_id'=>$id]);
        }
        return $query;
    }
    /**
     * 后台 - 新商品管理- 获取商品详情数据
     * @create 2017/6/6
     * @author lihuien<lihuien@itsports.club>
     * @param $id int  商品ID
     * @return array
     */
    public function getGoodsDetailData($id)
    {
       return self::find()->where(['id'=>$id])->asArray()->one();
    }
    /**
     * 后台 - 新商品管理- 获取商品的数据
     * @create 2017/6/6
     * @author houkaixin<houkaixin@itsports.club>
     * @param $goodsId int  商品ID
     * @return array
     */
    public function getTheData($goodsId){
        $query = Goods::find()->joinWith(["goodsType","goodsDetail","goodsChange"],false)
            ->select("   cloud_goods.id,
                        cloud_goods.goods_type_id,  
                        cloud_goods.goods_brand as goodsBrand,
                        cloud_goods.goods_name as goodsName,
                        cloud_goods.unit_price as unitPrice,
                        cloud_goods.unit,
                        cloud_goods.store_id,
                        cloud_goods.goods_model as goodsModel,
                        cloud_goods.goods_producer as goodsProducer,
                        cloud_goods.goods_supplier as goodsSupplier,
                        cloud_goods_detail.storage_num,                     
                        cloud_goods_type.goods_type,
                        cloud_goods_change.list_num as goodsListNum,
                        cloud_goods_change.status,
                        sum(cloud_goods_change.operation_num) as intoNum,                     
                        cloud_goods_change.describe,
                        cloud_goods.venue_id          
                        ")
            ->where(["cloud_goods.id"=>$goodsId])
            ->groupBy("cloud_goods.id")
            ->asArray()->one();
        return $query;
    }
    /**
     * 后台 - 验卡管理 - 获取指定类型下的商品
     * @create 2017/6/6
     * @author houkaixin<houkaixin@itsports.club>
     * @param $goodsTypeId    //商品ID
     * @return array
     */
    public function getTheGoods($goodsTypeId){
          $goods = \common\models\base\Goods::find()->where(["goods_type_id"=>$goodsTypeId])->asArray()->all();
          return $goods;
    }

   public function getVenueGoods($type,$id){
       $data = Goods::find()->alias("goods")
               ->joinWith(["goodsType goodsType"])->select("goods.id,goods.goods_name,goodsType.venue_id,goodsType.company_id,goods.goods_type_id")->asArray();
       if(!empty($type)&&$type == "venue"){
           $data = $data->andFilterWhere(["goodsType.venue_id"=>$id]);
       }
       if(!empty($type)&&$type == "company"){
           $data = $data->andFilterWhere(["goodsType.company_id"=>$id]);
       }
           $data = $data->orderBy(["goods.create_time"=>SORT_DESC])->all();
        return $data;
   }

    /**
     * 后台仓库管理 - 仓库列表 - 仓库信息查询
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/28
     * @param $params //搜索参数
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $this->customLoads($params);
        $query = Goods::find()
            ->alias('goods')
            ->joinWith(['storeHouse storeHouse'],false)
            ->joinWith([
                'storeHouse storeHouse'=>function($query){
                    $query->joinWith(['organization organization']);
                }
            ])
//            ->joinWith(['organization organization'],false)
            ->joinWith(['goodsDetail goodsDetail'],false)
            ->joinWith(['goodsType goodsType'],false)
            ->joinWith(['goodsChangeS goodsChangeS'],false)
//            ->joinWith(['goodsChangeS goodsChangeS'=>function($query){
//               $query->groupBy(["goodsChangeS.goods_id"]);
//               $query->andFilterWhere(['or',['goodsChangeS.status'=>1],['goodsChangeS.status'=>6]]);
//            }],false)
            ->select(
                "
                storeHouse.id as storeHouseId,
                storeHouse.name,
                storeHouse.venue_id,
                organization.id as venueId,
                organization.name as venueName,
                goods.id,
                goods.goods_name,
                goods.goods_attribute,
                goods.store_id,
                goods.goods_type_id,
                goods.create_time,
                goodsType.goods_type,
                goods.goods_model,
                goodsDetail.storage_num,
                goodsDetail.unit_price,
                goodsChangeS.unit_price as unitPrice,
                goodsChangeS.surplus_amount,
                sum(goodsChangeS.unit_price * goodsChangeS.surplus_amount) as allMany,
                "
            )
            ->groupBy(["goods.id"])
            ->orderBy($this->sorts)
            ->asArray();
        $query                 = $this->getSearchWheres($query);
        $dataProvider          =  Func::getDataProvider($query,8);
        return  $dataProvider;
    }

    /**
     * 后台仓库管理 - 仓库列表 - 仓库信息查询
     * @create 2017/8/28
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return bool
     */
    public function customLoads($data)
    {
        $this->keywords     = (isset($data[self::KEY]) && !empty($data[self::KEY])) ? $data[self::KEY] : null;
        $this->type         = (isset($data[self::TYPE]) && !empty($data[self::TYPE])) ? (int)$data[self::TYPE] : null;
        $this->startTime    = (isset($data[self::START])&& !empty($data[self::START]))? (int)strtotime($data[self::START]) : null;
        $this->endTime      = (isset($data[self::END]) && !empty($data[self::END])) ? (int)strtotime($data[self::END]) : null;
        $this->venueId      = (isset($data[self::VENUE_ID]) && !empty($data[self::VENUE_ID])) ? (int)$data[self::VENUE_ID] : null;
        $this->category     = (isset($data[self::CATEGORY]) && !empty($data[self::CATEGORY]))?$data[self::CATEGORY]: NULL;
//        $this->goodsBrands  = (isset($data[self::GOODS_BRANDS]) && !empty($data[self::GOODS_BRANDS]))?$data[self::GOODS_BRANDS]: NULL;
//        $this->goodsModel   = (isset($data[self::GOODS_MODEL]) && !empty($data[self::GOODS_MODEL]))?$data[self::GOODS_MODEL]: NULL;
        $this->nowBelongId  = (isset($data[self::NOW_BELONG_ID]) && !empty($data[self::NOW_BELONG_ID]))?$data[self::NOW_BELONG_ID]: NULL;
        $this->nowBelongType= (isset($data[self::NOW_BELONG_TYPE]) && !empty($data[self::NOW_BELONG_TYPE]))?$data[self::NOW_BELONG_TYPE]: NULL;
        $this->sorts = self::loadSorts($data);

        return true;
    }

    /**
     * 仓库管理 - 列表 - 获取排序条件
     * @create 2017/8/28
     * @author huanghua<huanghua@itsports.club>
     * @param $data
     * @return mixed
     */
    public static function loadSorts($data)
    {
        $sorts = [
            'id' => SORT_DESC
        ];
        if (!isset($data['sortType'])) {
            return $sorts;
        }
        switch($data['sortType'])
        {
            case 'ht_id'          :
                $attr = '`storeHouse`.id';
                break;
            case 'ht_name'           :
                $attr = '`storeHouse`.name';
                break;
            case 'ht_venue'           :
                $attr = '`organization`.name';
                break;
            case 'ht_goodsName'        :
                $attr = '`goods`.goods_name';
                break;
            case 'ht_type'        :
                $attr = '`goods`.goods_attribute';
                break;
            case 'ht_category'        :
                $attr = '`goodsType`.goods_type';
                break;
            case 'ht_goodsModel'        :
                $attr = '`goods`.goods_model';
                break;
            case 'ht_stockNum'        :
                $attr = '`goodsDetail`.storage_num';
                break;
            case 'ht_totalAmount'        :
                $attr = '`goodsDetail`.unit_price';
                break;
            case 'ht_createTime'        :
                $attr = '`goods`.create_time';
                break;
            default;
                return $sorts;
        }
        return $sorts = [ $attr  => self::convertSortValue($data['sortName']) ];

    }

    /**
     * 仓库管理 - 仓库列表 - 增加搜索条件
     * @create 2017/8/28
     * @author huanghua<huanghua@itsports.club>
     * @param $query
     * @return mixed
     */
    public function getSearchWheres($query)
    {
        $query->andFilterWhere([
            'or',
            ['like','storeHouse.name', $this->keywords],
            ['like','goods.goods_name', $this->keywords],
            ['like','goods.goods_brand', $this->keywords],
            ['like','goods.goods_model', $this->keywords]

        ]);
        $query->andFilterWhere([
            'and',
            [
                'storeHouse.venue_id' => $this->venueId,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            ['>=','goods.create_time',$this->startTime],
            ['<','goods.create_time',$this->endTime]
        ]);

        $query->andFilterWhere([
            'and',
            [
                'goods.goods_attribute' => $this->type,
            ],
        ]);
        $query->andFilterWhere([
            'and',
            [
                'goods.goods_type_id' => $this->category,
            ],
        ]);
//        $query->andFilterWhere([
//            'and',
//            [
//                'goods.goods_model' => $this->goodsModel,
//            ],
//        ]);
//        $query->andFilterWhere([
//            'and',
//            [
//                'goods.goods_brand' => $this->goodsBrands,
//            ],
//        ]);
        if($this->nowBelongType && $this->nowBelongType == 'company'){
            $query->andFilterWhere(['goods.company_id'=>$this->nowBelongId]);
        }
        if($this->nowBelongType && ($this->nowBelongType == 'venue' || $this->nowBelongType == 'department')){
            $query->andFilterWhere(['goods.venue_id'=>$this->nowBelongId]);
        }
        return $query;
    }

    /**
     * 云运动 - 仓库管理 - 商品出库变更数据计算
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/7
     * @param $params
     * @return boolean/object
     */
    public function getNewGoodsId($params)
    {
        $goods = Goods::find()
            ->alias('goods')
            ->joinWith(['goodsType goodsType'],false)
            ->where(['store_id'=>$params['beStoreId']])
            ->andWhere(['and',['goods.goods_attribute' =>$params['goodsAttribute']],['goods.unit'=>$params['unit']]])
            ->andWhere(['and',['goods.goods_name' =>$params['goodsName']],['goodsType.goods_type'=>$params['goodsTypeName']]])
            ->select("goods.id")
            ->asArray()
            ->one();
        return $goods;
    }

}
