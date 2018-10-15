<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/14
 * Time: 8:35
 */

namespace common\models\relations;
use common\models\Organization;
use common\models\base\ImageManagementType;
trait PictureRelations
{
    /**
     * 图片管理 - 关联组织架构表
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/8/11
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization(){
        return $this->hasOne(Organization::className(),['id'=>'venue_id']);
    }

    public function getOrganizations(){
        return $this->hasOne(Organization::className(),['id'=>'company_id']);
    }
    public function getImageManagementType(){
        return $this->hasOne(ImageManagementType::className(),['id'=>'type']);
    }
}