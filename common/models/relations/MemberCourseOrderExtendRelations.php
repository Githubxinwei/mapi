<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/8/2
 * Time: 10:20
 * Desc:会员购买私课订单详情扩展表
 */
namespace common\models\relations;

use common\models\MemberCourseOrderDetails;

trait  MemberCourseOrderExtendRelations
{
    /**
     * 会员端 - API - 关联会员购买私课订单详情表
     * @return string
     * @author 辛威
     * @create 2018-08-02
     * @return mixed
     */
    public function getMemberCourseOrderDetails()
    {
        return $this->hasOne(MemberCourseOrderDetails::className(), ['id' => 'details_id']);
    }
}