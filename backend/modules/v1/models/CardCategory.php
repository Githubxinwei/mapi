<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/6 0006
 * Time: 上午 9:55
 */

namespace backend\modules\v1\models;
use backend\models\LimitCardNumber;
use common\models\base\BindPack;
use common\models\base\CardCategoryType;
use common\models\base\MemberDetails;
use common\models\Course;
use common\models\relations\CardCategoryRelations;


class CardCategory extends \common\models\base\CardCategory
{
    use CardCategoryRelations;
    const CARD_PIC = 'http://oo0oj2qmr.bkt.clouddn.com/card_default_img.png';
    /**
     * ios - 卡种信息 - 查询所有卡种
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @param $venueId                        //会员的场馆id
     * @param $cardTypeId                     //卡种类型id
     * @return array|\yii\db\ActiveRecord[]   //符合条件的卡种信息
     */
    public function getCardCategory($venueId,$cardTypeId = '')
    {
        //1.查询卡种表获取卡种并且获取卡种类别,以售卖场馆，售卖时间，售卖数量为条件
        $data = CardCategory::find()
            ->alias('category')
            ->joinWith(['cardCategoryType type'],false)
            ->joinWith(['limitCardNumber limit'],false)
            ->select('
                        category.id,
                        category.pic,
                        category.category_type_id,
                        category.card_name,
                        category.status,
                        category.sell_price,
                        category.min_price,  
                        category.max_price,
                        category.original_price, 
                        category.duration,
                        category.app_sell_price,
                        type.id as typeId,
                        type.type_name,
                        limit.venue_id as venueId,
                        limit.sell_start_time,
                        limit.sell_end_time,
                        limit.surplus,
                        category.create_at
                        ')
            ->where(["category.venue_id"=>$venueId])
            ->andWhere(["is_app_show"=>1])
          //  ->andWhere(["in","category.card_name",["999元月卡","D12M MD","WD T12 MD","WD T24MD"]])
            ->andWhere(["category.status"=>1])
            ->andWhere(['limit.venue_id'=>$venueId])
            ->andWhere(['<=','limit.sell_start_time',time()])
            ->andWhere(['>=','limit.sell_end_time',time()])
            ->andWhere(['<>','limit.surplus',0])
            ->andWhere(['IS NOT','category.duration',null])
            ->andWhere(['<>','category.card_type',5])
            ->andFilterWhere(['category_type_id'=>$cardTypeId])
            ->orderBy(["category.create_at"=>SORT_DESC])
            ->asArray()
            ->all();
        if(empty($data)){
            return $data;
        };
//        return $data;
        //2.取卡种价格，和有效期
        foreach($data as $k=>&$v)
        {
            $data[$k]['pic'] = empty($data[$k]['pic'])?self::CARD_PIC:$data[$k]['pic'];
//            if(!empty($v['sell_price']))
//            {
//                $v['price'] = $v['sell_price'];                       //售价存在取售价
//            }else{
//                $v['price'] = $v['min_price'];                        //售价不存在，取最低价
//            }
//            if(!empty($v['sell_price']))
//            {
//                $v["original_price"] = !empty($v["original_price"])?$v["original_price"]:0;
//                $resultPrice = ($v['sell_price']>$v["original_price"])?$v['sell_price']:$v["original_price"];
//                $v['price'] = $resultPrice;                       //售价存在取售价
//            }else{
//                // $v['price'] = $v['min_price'];                        //售价不存在，取最低价
//                $resultPrice = ($v["max_price"]>$v["min_price"])?$v['max_price']:$v["min_price"];
//                $v["price"]  = $resultPrice;
//            }
            $data[$k]["price"] = empty($data[$k]["app_sell_price"])?"":$data[$k]["app_sell_price"];
            $validPeriod = json_decode( $v['duration']);              //转换json格式中的有效期
            $v['validPeriod'] =  $validPeriod->day ;                    //取出有效天数
            unset(
                $v['category_type_id'],
                $v['typeId'],
                $v['venueId'],
                $v['duration'],
                $v['sell_price'],
                $v['min_price'],
                $v['sell_start_time'],
                $v['sell_end_time'],
                $v['surplus']
            );  //处理无用字段
        }
        return $data;
    }

    /**
     * ios - 卡种信息 - 查询卡种详情
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @param $cardCategoryId                   //卡种id
     * @return array|null|\yii\db\ActiveRecord  //卡种详情
     */
    public function getCardCategoryDetail($cardCategoryId)
    {
        //1.获取卡种信息
        $data =  CardCategory::find()
            ->alias('category')
            ->joinWith(['limitCardNumberAll limit'=>function($query){
                $query->joinWith(['organization or'],false)
                    ->select('limit.card_category_id,limit.venue_id,or.id,or.name as venueName');
            }])
            ->joinWith(['cardCategoryType type'],false)
            ->joinWith(['deal deal'],false)
            ->select('
                        category.id,
                        category.pic,
                        category.category_type_id,
                        category.card_name,
                        category.attributes,
                        category.sell_price,
                        category.min_price,  
                        category.max_price,
                        category.app_sell_price,
                        category.original_price, 
                        category.duration,
                        category.transfer_number,
                        category.transfer_price,
                        category.leave_total_days,
                        category.leave_least_Days,
                        category.leave_long_limit,
                        type.id as typeId,
                        type.type_name,
                        deal.id as deal_id,
                        deal.name as deal_name,
                        or.name as venueName
                 ')
            ->where(['category.id'=>$cardCategoryId])
            ->asArray()
            ->one();
        if ($data) {
            $venues =  $data["limitCardNumberAll"];
            $data["limitCardNumberAll"] =[];
            $goVenueData = LimitCardNumber::find()
                ->alias('lcn')
                ->where(["lcn.card_category_id"=>$cardCategoryId])
                ->andWhere(['or',['IS NOT','lcn.times',NULL],['IS NOT','lcn.week_times',NULL]])
                ->joinWith(["organization"],false)
                ->select("lcn.*,cloud_organization.name as venueName,cloud_organization.identity")
                ->asArray()->all();
            if ($goVenueData) {
                foreach ($goVenueData as $key => $value){
                    if(empty($value['name'])){
                        $venueIds = json_decode($value['venue_ids'],true);
                        $value['organization'] = Organization::find()->where(['id'=>$venueIds])->select('id,name as venueName,identity')->asArray()->all();
                        array_push($data["limitCardNumberAll"],$value);
                    }else{
                        array_push($data["limitCardNumberAll"],$value);
                    }
                }
            }
            if ($venues) {
                foreach ($venues as $key => $val){
                    if ($val['venueName'] !== null){
                        unset($val['card_category_id']);
                        unset($val['venue_id']);
                        $val['identity']='1';
                        $array[]=$val;
                    }
                }
            }
            $arr = [];
            foreach ($data["limitCardNumberAll"] as $venue){
                foreach ($venue['organization'] as $vanuename){
                    array_push($arr,$vanuename);
                }
            }
            $val = [];
            $res = array_merge($arr,$array);
            if ($res) {
                foreach ($res as $k => $v){
                    if (!in_array($v,$val)){
                        array_push($val,$v);
                    }
                }
            }
        }
        // 对图片的处理
        $data["pic"] = !empty($data["pic"])?$data["pic"]:self::CARD_PIC;
        if(!empty($data)) {
            //2.处理有效天数
            $validPeriod = json_decode($data['duration']);                  //转换json格式中的有效期
            $data['validPeriod'] = $validPeriod->day;                       //取出有效天数
            //3.处理价格
//            if (!empty($data['sell_price'])) {
//                $data['price'] = $data['sell_price'];                       //售价存在取售价
//            } else {
//                $data['price'] = $data['min_price'];                        //售价不存在，取最低价
//            }
//            if(!empty($data['sell_price']))
//            {
//                $data["original_price"] = !empty($data["original_price"])?$data["original_price"]:0;
//                $resultPrice = ($data['sell_price']>$data["original_price"])?$data['sell_price']:$data["original_price"];
//                $data['price'] = $resultPrice;
//            }else{
//                // $v['price'] = $v['min_price'];                        //售价不存在，取最低价
//                $resultPrice = ($data["max_price"]>$data["min_price"])?$data['max_price']:$data["min_price"];
//                $data["price"]  = $resultPrice;
//            }
            $data["price"] = empty($data["app_sell_price"])?"":$data["app_sell_price"];
            //4.处理请假天数
            if (!empty($data['leave_least_Days']) || !empty($data['leave_total_days'])) {
                $data['leastDay'] = $data['leave_least_Days'];               //每次最低天数
                $data['totalDay'] = $data['leave_total_days'];               //请假总天数
                unset($data['leave_long_limit']);                            //删除另一种情况
            } else if ($data['leave_long_limit'] != 'null') {
                $day = json_decode($data['leave_long_limit']);              //转换json格式中的请假天和次数
                $totalDay = 0;
                $leastArr = [0];                                             //定义空数组存放每次请假的天数
                if(is_array($day)){
                    foreach ($day as $k => $v) {
                        $totalDay += $v[0] * $v[1];                            //计算总天数
                        $leastArr[$k] = $v[0];                                  //存放每次请假的天数
                    }
                }
                $data['totalDay'] = $totalDay;                            //请假总天数
                $data['leastDay'] = min($leastArr);                       //每次请假最少天数
            } else {
                $data['totalDay'] = 0;                                    //请假总天数
                $data['leastDay'] = 0;                                    //每次请假最少天数
            }
            //5.处理转让次数和金额
            if (empty($data['transfer_number'])) {
                $data['transfer_number'] = 0;                                //处理转让次数
            }
            if (empty($data['transfer_price'])) {
                $data['transfer_price'] = 0;                                  //处理转让金额
            }
            //6.处理通店场馆
            $shopVenue = '';
            foreach ($val as $k => $v) {
                $shopVenue .= $v['venueName'] . '/';                            //处理通店场馆名称
            }
            $allShopVenue = rtrim($shopVenue, '/');                       //删除最后字符
            //去重
            $allShopVenues = explode("/",$allShopVenue);
            $generalShopVenue = array_unique($allShopVenues);
            $data['shopVenue'] = implode("/",$generalShopVenue);
            //7.处理卡种属性
            if($data['attributes'] == 1)
            {
                $data['attributes'] = '个人';
            }else if($data['attributes'] == 2)
            {
                $data['attributes'] = '家庭';
            }else if($data['attributes'] == 3)
            {
                $data['attributes'] = '公司';
            }
            unset(                                                           //删除无用字段
                $data['category_type_id'],
                $data['duration'],
                $data['sell_price'],
                $data['min_price'],
                $data['leave_total_days'],
                $data['leave_least_Days'],
                $data['leave_long_limit'],
                $data['typeId'],
                $data['limitCardNumberAll']
            );
            return $data;
        }else{
            return NULL;
        }
    }

    /**
     * ios - 卡种信息 - 查询卡种绑定信息
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @param $cardCategoryId               //卡种id
     * @param $type                         //卡种类型
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPackage($cardCategoryId,$type)
    {
        if($type == 'class')
        {
            $data = $this->getClassPackage($cardCategoryId,$type);
            return $data;
        }else if($type == 'server')
        {
            $data = $this->getServerPackage($cardCategoryId,$type);
            return $data;
        }
    }

    /**
     * ios - 卡种信息 - 查询课程
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @param $cardCategoryId               //卡种id
     * @param $type                         //类型 为 class的
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getClassPackage($cardCategoryId,$type)
    {
/*        $data = \common\models\BindPack::find()
            ->alias('bind')
            ->joinWith(['course course'],false)
            ->select('
                    bind.id,
                    bind.polymorphic_id,
                    bind.number,
                    course.id as courseId,
                    course.name 
                ')
            ->where(['bind.card_category_id'=>$cardCategoryId])
            ->andWhere(['bind.polymorphic_type'=>$type])
            ->asArray()
            ->all();*/
        $data = [];
        $binds = BindPack::find()->where(['card_category_id'=>$cardCategoryId, 'polymorphic_type'=>$type])->all();
        if($binds){
            foreach ($binds as $bind) {
                $course_ids = json_decode($bind->polymorphic_ids);
                $course_ids[] = $bind->polymorphic_id;
                $courses = Course::find()->where(['id'=>$course_ids])->all();
                if($courses){
                    foreach ($courses as $course){
                        $data[] = [
                            'id' => $bind->id,
                            'polymorphic_id' => $course->id,
                            'number' => $bind->number,
                            'courseId' => $course->id,
                            'name' => $course->name,
                        ];
                    }
                }
            }
        }
        return $data;
    }

    /**
     * ios - 卡种信息 - 查询服务
     * @create 2017/7/6
     * @author huangpengju<huangpengju@itsports.club>
     * @param $cardCategoryId                   //卡种id
     * @param $type
     * @return array|\yii\db\ActiveRecord[]     //类型 server
     */
    public function getServerPackage($cardCategoryId,$type)
    {
        $data = \common\models\BindPack::find()
            ->alias('bind')
            ->joinWith(['server server'],false)
            ->select('
                    bind.id,
                    bind.polymorphic_id,
                    bind.number,
                    server.id as serverId,
                    server.name 
                ')
            ->where(['bind.card_category_id'=>$cardCategoryId])
            ->andWhere(['bind.polymorphic_type'=>$type])
            ->asArray()
            ->all();
        return $data;
    }

    /**
     * ios - 卡种信息 - 查询卡种类型
     * @create 2017/7/7
     * @author huangpengju<huangpengju@itsports.club>
     * @return array|\yii\db\ActiveRecord[]  //返回所有卡种类型
     */
    public function getCardType()
    {
        return CardCategoryType::find()->select('id,type_name')->asArray()->all();
    }
    /**
     * ios - 卡种信息 - 判断会员信息是否完善
     * @create 2017/7/7
     * @author 侯凯新<houkaixin@itsports.club>
     * @param $memberId     // 会员id
     * @return  boolean    //用来判断会员信息是否完善
     */
    public function judgeMemberMessage($accountId,$venue_id){
        if(empty($accountId)|| empty($venue_id)){
            return false;
        }
        $memberId = array_column(Member::find()->where(["member_account_id"=>$accountId,'venue_id'=>$venue_id])->all(),'id');
        $searchMember = MemberDetails::find()
            ->where(["member_id"=>$memberId])
            ->one();
        if(empty($searchMember->name)||empty($searchMember->sex)||empty($searchMember->id_card)){
            return false;
        }
        return true;

    }



}
