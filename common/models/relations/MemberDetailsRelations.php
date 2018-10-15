<?php

namespace common\models\relations;


use backend\models\Member;
use common\models\Config;
use common\models\Evaluate;

trait MemberDetailsRelations
{
    /**
     * 后台会员管理 - 潜在会员来源信息查询 - 关联配置表
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/6/28
     * @return mixed
     */
    public function getConfig()
    {
        return $this->hasOne(Config::className(),['id'=>'way_to_shop']);  
    }
    /**
     * 后台会员管理 - 潜在会员来源信息查询 - 关联会员表
     * @author huangpengju <huangpengju@itsports.club>
     * @create 2017/7/2
     * @return mixed
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }
    /**
     * 会员端 - 课程评价 - 关联课程评价表
     * @author 辛未 <xinwei@itsports.club>
     * @create 2018/06/04
     * @return mixed
     */
    public function getEvaluate(){
        return $this->hasOne(Evaluate::className(),['member_id' => 'member_id']);
    }
}