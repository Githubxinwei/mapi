<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27 0027
 * Time: 上午 10:11
 */
namespace common\models\relations;
use common\models\base\FitnessDiet;
trait MemberFitnessProgramRelations
{
    /**
     * 后台会员维护 - 获取会员健身详情 - 关联健身饮食表（健身目标）
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/27
     * @return \yii\db\ActiveQuery
     */
    public function getFitness()
    {
        return $this->hasOne(FitnessDiet::className(),['id'=>'fitness_id']);
    }
    /**
     * 后台会员维护 - 获取会员健身详情 - 关联健身饮食表（饮食计划）
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/27
     * @return \yii\db\ActiveQuery
     */
    public function getDiet()
    {
        return $this->hasOne(FitnessDiet::className(),['id'=>'diet_id']);
    }
}