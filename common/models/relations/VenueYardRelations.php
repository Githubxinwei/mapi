<?php

namespace common\models\relations;
use common\models\base\AboutYard;
use common\models\base\VenueYardCardcategory;
use common\models\Organization;
trait  VenueYardRelations{
    /**
     * 后台 - 场馆场地表 关联 场馆表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/09/07
     */
    public function  getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
    /**
     * 后台 - 场馆场地表 关联 场地适用卡种表
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/9/7
     */
    public function getVenueYardCardCategory(){
        return $this->hasMany(VenueYardCardcategory::className(),['yard_id'=>'id']);
    }
    /**
     * 后台 - 场地表关联 会员场地预约
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/9/7
     */
    public function getAboutYard(){
        return $this->hasMany(AboutYard::className(),['yard_id'=>'id'])->where(["and",["aboutDate"=>constant('memberAboutDate')],["!=","status",5]]);
    }
    /**
     * 后台 - 场地表关联 会员场地预约(外加特殊条件)
     * @author houkaixin<houkaixin@itsports.club>
     * @create 2017/9/7
     */
    public function getAboutYardS(){
        $date = date("Y-m-d",time());
        return $this->hasMany(AboutYard::className(),['yard_id'=>'id'])->where(["and",["aboutDate"=>constant('memberAboutDate')],["!=","status",5]]);
    }


}
?>