<?php
namespace common\models\relations;

use backend\modules\v1\models\MemberCourseOrderDetails;
use common\models\base\Organization;
trait  evaluateRelations
{
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }
    public function getmemberCourseOrderDetails(){
        return $this->hasOne(MemberCourseOrderDetails::className(),['id'=>'consumption_type_id']);
    }
    public function getAboutClass()
    {
        return $this->hasOne(\common\models\AboutClass::className(),['class_id'=>'id']);
    }
    public function getMemberDetails()
    {
        return $this->hasOne(\common\models\MemberDetails::className(),['member_id'=>'member_id']);
    }

}