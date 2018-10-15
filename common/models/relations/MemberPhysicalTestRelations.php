<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 12:10
 * Desc:体侧信息
 */
namespace common\models\relations;

use common\models\Member;
use common\models\PhysicalTest;
use common\models\FitnessAssessment;

trait MemberPhysicalTestRelations
{
    /**
     * 会员管理 - 体测信息查询 - 关联会员表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/06/25
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
    /**
     * 会员管理 - 体测查询 - 体测数据表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/06/30
     * @return \yii\db\ActiveQuery
     */
    public function getPhysicalTest()
    {
        return $this->hasOne(PhysicalTest::className(), ['id' => 'cid']);
    }
    /**
     * 会员管理 - 体适能查询 - 体适能评估表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/06
     * @return \yii\db\ActiveQuery
     */
    public function getFitness()
    {
        return $this->hasOne(FitnessAssessment::className(), ['id' => 'cid']);
    }
}