<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 上午 9:02
 */

namespace backend\modules\v1\models;
use common\models\base\Member;
use common\models\base\MemberDetails;
use yii\base\Model;


class BindMemberInfoForm extends Model
{
    public $idCard;        //会员头像
    public $accountId;   //账号id
    public $mobile;    //手机号
    public $name;
    public $sex;
    public $venueId;   //场馆ID
    /**
     * 后台 - 登录 - 验证规则
     * @author  huangpengju <huangpengju@itsport.club>
     * @create 2017/6/23
     */
    public function rules()
    {
        return [
            ['mobile','required','message'=>'手机号不能为空'],
            ['accountId','required','message'=>'账户不能为空'],
            ['idCard','required','message'=>'身份证号不能为空'],
            [['mobile','memberId','venueId','sex',"name"],'safe']
        ];
    }
    /**
     * 后台 - API - 修改会员详细信息
     * @author  huangpengju <huangpengju@itsport.club>
     * @create 2017/6/23
     */
    public function updateMember()
    {
        //1.先去查会员和详细信息表，有就存，没有就添加
        $member = Member::findOne(['member_account_id'=>$this->accountId]);
        $detail = MemberDetails::findOne(['member_id'=>$member->id]);
        $mVOne  = Member::findOne(['venue_id'=>$this->venueId,'member_account_id'=>$member->member_account_id]);
        if(!empty($mVOne) && isset($mVOne->id)){
            return ['id'=>$mVOne->id];
        }
        $model  = new Member();
        $model->username          = $member->username;
        $model->password          = $member->password;
        $model->company_id        = $member->company_id;
        $model->member_account_id = $member->member_account_id;
        $model->register_time     = time();
        $model->venue_id          = $this->venueId;
        $model->member_type       = 2;
        $model->mobile            = $member->mobile;
        if($model->save()){
            $md = new MemberDetails();
            $md->name              = $this->name;
            $md->id_card           = $this->idCard;
            $md->ic_number         = $detail->ic_number;
            $md->pic               = $detail->pic;
            $md->created_at        = time();
            $md->birth_date        = $detail->birth_date;
            $md->family_address    = $detail->family_address;
            $md->fingerprint       = $detail->fingerprint;
            $md->sex               = $this->sex;
            $md->document_type     = $detail->document_type;
            $md->now_address       = $detail->now_address;
            $md->note              = $detail->note;
            $md->nickname          = $detail->nickname;
            $md->member_id         = $model->id;
            if($md->save()){
                return ['id'=>$model->id];
            }
            return false;
        }
        return false;
    }
}