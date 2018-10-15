<?php
namespace backend\models;

use common\models\relations\MemberDetailsRelations;

class MemberDetails extends \common\models\MemberDetails
{
     use MemberDetailsRelations;
    /**
     * 云运动 - 会员管理 - 查询会员身份证号
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/4/25
     * @param $MemberId      //会员id
     * @param $MemberIdCard  //会员身份证号
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function getMemberIdCard($MemberId,$MemberIdCard,$venueId)
    {
        if($MemberIdCard) {
            $model = MemberDetails::find()
                ->alias('md')
                ->joinWith(['member member'])
                ->filterWhere(['<>','member_id',$MemberId])
                ->andWhere(['and',['id_card'=>$MemberIdCard],['member.venue_id'=>$venueId]])
                ->select('md.id,md.ic_number,md.member_id,member.venue_id')
                ->asArray()
                ->one();
            return $model;
        }else{
            return null;
        }
    }

    /**
     * @私课管理 - 私课下课打卡 - 会员录入指纹
     * @author zhumengke <huanghua@itsports.club>
     * @create 2017/9/22
     * @return bool|string
     */
    public function inputFingerprint($data)
    {
        $details = \common\models\base\MemberDetails::findOne(['member_id' => $data['memberId']]);
        $details->fingerprint = $data['fingerprint'];
        if($details->save() == true){
            return true;
        }else{
            return $details->errors;
        }
    }

    /**
     * 云运动 - 会员管理 - 根据会员id获取身份证号
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/9/28
     * @param  $memberId
     * @return array
     */
    public function getMemberCardId($memberId)
    {
        return  MemberDetails::find()
            ->alias("md")
            ->select("md.id,md.id_card,md.document_type")
            ->where(['member_id'=>$memberId])
            ->asArray()
            ->one();
    }

    /**
     * 后台会员管理 - 会员详情 - 指纹删除
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/11/6
     * @param $id
     * @return bool
     */
    public  function getUpdateMemberDetails($id)
    {
        $memberDetails  =  \common\models\base\MemberDetails::findOne(['member_id'=>$id]);

        if(!empty($memberDetails['fingerprint'])){
            $memberDetails->fingerprint = null;
        }else{
            return "该会员没有指纹，无需删除";
        }
        if($memberDetails->save()){
            return true;
        }else{
            return $memberDetails->errors;
        }
    }

    /**
     * 后台会员管理 - 会员详情 - 会员头像删除
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/12/13
     * @param $memberId  //会员id
     * @return bool
     */
    public function delMemberPhoto($memberId)
    {
        $memberDetails = \common\models\base\MemberDetails::findOne(['member_id' => $memberId]);
        if(!empty($memberDetails['pic'])){
            $memberDetails->pic = NULL;
        }else{
            return "该会员没有头像，无需删除";
        }
        if($memberDetails->save()){
            return true;
        }else{
            return $memberDetails->errors;
        }
    }
}