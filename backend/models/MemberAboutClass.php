<?php

namespace backend\models;

use common\models\ArrayConfig;
use yii\base\Model;

class MemberAboutClass extends Model
{
    public $identity;     // 会员卡级别
    /**
     * 后台 - 会员约课 - 判断能不能约
     * @author lihuien <lihuien@itsports.club>
     * @update huangpengju
     * @update 2017/6/9
     * @param  $query           //课程信息
     * @param $class            //课种类型
     * @param $member           //会员信息
     * @param $memberCard       //会员卡信息
     * @return bool
     */
    public function statusMemberAboutClass(&$query,$class,$member,$memberCard,$category = '')
     {
          if(!empty($member) && ($member['member_type'] != 2 || $member['is_employee'] != 1)){                         //判断是普通会员，
                if(isset($memberCard['card_number'])){                                                                 //检测会员卡是否存在
                    if(preg_match('/^1000[0-9]*$/',$memberCard['card_number'])){                                        //正则匹配存在的会员卡号（1000xxxx 尊黑）都可以预约
                        $query['identify'] = 2;                                                                  //会员座位权限 （随意挑）
                        $this->identity    = 2;
                        return true;
                    }elseif (preg_match('/^090[01][0-9]*$/',$memberCard['card_number'])){             //正则匹配存在的会员卡号（0900xxxx/0901xxxx）可以约 瑜伽
                        $query['identify'] = 1;                                                                   //会员座位权限  （限制vip）
                        $this->identity    = 1;
                        return true;
                    }elseif (preg_match('/^070000[0-9]*$/',$memberCard['card_number'])){       //正则匹配存在的会员卡号（070000xxx）可以约单车
                      if(!is_bool(strpos('单车',$class)) || !is_bool(strpos('单车',$category))){
                          $query['identify'] = 2;                                                                  //会员座位权限  （随意挑）
                          $this->identity    = 2;
                          return true;
                      }
                          return false;
                    }elseif (preg_match('/^3710[0-9]*$/',$memberCard['card_number'])){                                 //正则匹配存在的会员卡号（3710xxxx）
                         $query['identify'] = 2;                                                             //会员座位权限  （随意挑）
                         $this->identity    = 2;
                         return true;
                    }elseif (preg_match('/^016[0-9]*$/',$memberCard['card_number'])){                                  //正则匹配存在的会员卡号（016xxxx）可以
                        $query['identify'] = 2;                                                                  //会员座位权限  （随意挑）
                        $this->identity    = 2;
                        return true;
                    }elseif (preg_match('/^80[0-9]*$/',$memberCard['card_number'])){                                     //正则匹配存在的会员卡号（80开头 五位数）可以
                        $query['identify'] = 2;                                                                  //会员座位权限  （随意挑）
                        $this->identity    = 2;
                        return true;
                    }elseif (preg_match('/^10100[0-9]*$/',$memberCard['card_number'])){                                     //正则匹配存在的会员卡号（80开头 五位数）可以
                        $query['identify'] = 2;                                                                  //会员座位权限  （随意挑）
                        $this->identity    = 2;
                        return true;
                    }else{
                        return false;                                                                                  //其他都不让约课
                    }
                }
          }
          return true;                                                                                                 //都让约课
     }
    /**
     * 后台 - 会员约课 - 判断能不能约
     * @author lihuien <lihuien@itsports.club>
     * @update huangpengju
     * @update 2017/6/9
     * @param  $query           //课程信息
     * @param $class            //课种类型
     * @param $member           //会员信息
     * @param $memberCard       //会员卡信息
     * @return bool
     */
    public function statusMemberCardName(&$query,$class,$member,$memberCard,$category = '')
    {
        $cardArr = ArrayConfig::setCardNameArr();
        if(!empty($member) && ($member['member_type'] != 2 || $member['is_employee'] != 1)){                         //判断是普通会员，
            if(isset($memberCard['card_name'])){                                                                 //检测会员卡是否存在
                if (in_array($memberCard['card_name'],$cardArr['two'])){             //正则匹配存在的会员卡号（0900xxxx/0901xxxx）可以约 瑜伽
                    if(!is_bool(strpos('健身',$category))){
                        return true;
                    }else{
                        return false;
                    }
                }elseif (in_array($memberCard['card_name'],$cardArr['three'])){       //正则匹配存在的会员卡号（070000xxx）可以约单车
                    if(!is_bool(strpos('舞蹈',$class))){
                        return true;
                    }
                    return false;
                }elseif (in_array($memberCard['card_name'],$cardArr['four'])){                                 //正则匹配存在的会员卡号（3710xxxx）
                    if(!is_bool(strpos('舞蹈',$class)) || !is_bool(strpos('瑜伽',$class)) || !is_bool(strpos('健身',$class))){
                        return true;
                    }
                    return false;
                }else{
                    return true;                                                                                  //其他都不让约课
                }
            }
        }
        return true;                                                                                                 //都让约课
    }
    /**
     * 后台 - 会员卡数据过滤 - 对 过期未激活的卡 进行自动激活
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param $dataS     //课程信息
     * @return bool
     */
    public  static function filterData($dataS){
        $id = [];
        if(empty($dataS)){
            return $dataS;
        }
            foreach($dataS as $keys=>$data){
                    $createCardTime = $data["create_at"];
                    $activeNumDay   = $data["active_limit_time"];
                    $endActiveTime = $createCardTime + $activeNumDay*60*60*24;
                    if(!empty($data["memberCardId"])&&(empty($data["active_time"])&&time()>=$endActiveTime)){
                        $extendTime =  $data["invalid_time"] + $activeNumDay*60*60*24;
                        $dataS[$keys]["invalid_time"] = $extendTime;
                        $id[]  = $data["memberCardId"];
                    }
            }
            //如果到激活期限仍未激活 将激活期限改为 应该的激活期限
            //  并且 卡状态 进行更改
            if(isset($dataS['status']) && count($id)!=0 && $dataS['status'] == 4){
                
                $auth  = 60*60*24;
                $arr  = "(".implode($id,",").")";
                $query = \Yii::$app->db->createCommand("UPDATE cloud_member_card SET status = 1,active_time = create_at + active_limit_time*".$auth." WHERE id IN ".$arr)->execute();
            }
           return $dataS;
      }
    /**
     * 后台 -  验卡激活 -  对第一次验卡 （获取卡的 激活时间戳和失效时间戳）
     * @author 侯凯新 <houkaixin@itsports.club>
     * @param $memberCard     //会员卡信息
     * @param  $time         //有效天数
     * @return bool
     */
     public  static function editMemberCardData($memberCard,$time){
          $data = [];
          if(isset($memberCard)&&isset($time)){
                $endActiveTime = $memberCard["create_at"] + $memberCard["active_limit_time"]*60*60*24;
                if(time()>=$endActiveTime){
                    $data["activeTime"]  = $endActiveTime;                         // 卡的激活时间
                    $data["invalidTime"] = $endActiveTime + $time*60*60*24; // 卡的失效时间节点
                }else{
                    $data["activeTime"]  = time();                               //卡激活时间
                    $data["invalidTime"] = time()+ $time*60*60*24;    //失效时间戳节点
                }
          }
           return  $data;
     }
}