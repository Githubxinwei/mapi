<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 17:13
 * Desc:体适能评估表
 */
namespace common\models\relations;

use common\models\MemberPhysicalTest;

trait FitnessAssessmentRelations
{
    /**
     * 会员管理 - 体测查询 - 体测数据表
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/06/30
     * @return \yii\db\ActiveQuery
     */
    public function getMemberPhysicalTest()
    {
        return $this->hasOne(MemberPhysicalTest::className(), ['cid' => 'id']);
    }
}