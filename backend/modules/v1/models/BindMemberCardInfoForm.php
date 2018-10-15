<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/4/10
 * Time: 20:20
 * Desc: 绑定会员卡
 */
namespace backend\modules\v1\models;

use common\models\base\Member;
use common\models\base\MemberCard;
use common\models\base\BindMemberCardLog;
use backend\models\MemberDetails;
use Yii;
use yii\base\Model;

class BindMemberCardInfoForm extends Model
{
    public $cardNumber;        //会员卡号
    public $memberId;          //会员id
    public $accountId;         //账户id
    public $venueId;           //场馆id
    public $mobile;           //手机号
    public $document_type;    //证件类型
    public $id_card;           //证件号
    public $source;             //来源
    public $status;             //证件号匹配状态
    /**
     * 会员端 - 绑定用户 - 验证规则
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/4/10
     */
    public function rules()
    {
        return [
            ['cardNumber','required','message'=>'会员卡号不能为空'],
            ['mobile', 'required','message' => '手机号不能为空'],
            ['memberId','required','message'=>'会员id不能为空'],
            ['venueId','required','message'=>'场馆id不能为空'],
            ['accountId', 'required','message' => '账户id不能为空'],
            ['document_type','required','message'=>'证件类型不能为空'],
            ['id_card', 'required','message' => '证件号不能为空'],
            ['source', 'required','message' => '来源不能为空'],
        ];
    }
    /**
     * 会员端 - API - 绑定会员卡
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/4/10
     */
    public function updateMemberCard()
    {
        $memberCard = MemberCard::find()
            ->where(['card_number' => $this->cardNumber])
            ->select(['id','member_id','status','invalid_time','venue_id'])
            ->asArray()
            ->one();
        if (empty($memberCard['member_id'])) {
            return ['code' => 0, 'status' => 'error', 'message' =>'您输入的会员卡号不存在!'];
        } else {
            $memberId = $memberCard['member_id'];
        }
        if (empty($this->id_card)) {
            return ['code' => 0, 'status' => 'error', 'message' =>'证件号不能为空!'];
        }
        $member = Member::find()->where(['id' => $memberId])->select(['id','username','mobile','member_account_id'])->asArray()->one();
        if ($member['mobile'] == $this->mobile) {
            return ['code' => 0, 'status' => 'error', 'message' =>'此会员卡已绑定过您的手机号!'];
        }
        if ($this->isMobile($member['mobile']) && ($member['mobile'] <> $this->mobile)) {
            return ['code' => 0, 'status' => 'error', 'message' =>'该会员卡已绑定其它手机号!'];
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //更新老数据
            Member::updateAll(['mobile' => $this->mobile,'member_account_id' => $this->accountId],['id' => $memberId]);
            //删除新数据
            if ($memberCard['venue_id'] == $this->venueId) {
                Member::deleteAll(['mobile' => $this->mobile,'id' => $this->memberId]);
            }
            //查询证件类型和证件号
            $memberDetails = new MemberDetails();
            $memberDetails_data = $memberDetails->getMemberCardId($memberId);
            if (empty($memberDetails_data['document_type']) || empty($memberDetails_data['id_card'])) {
                $this->status = 2;
            } elseif (($memberDetails_data['document_type'] == $this->document_type) && ($memberDetails_data['id_card'] == $this->id_card)) {
                $this->status = 1;
            } elseif (($memberDetails_data['document_type'] <> $this->document_type) || ($memberDetails_data['id_card'] <> $this->id_card)) {
                $this->status = 3;
            }
            //生成会员绑卡日志记录
            $model = new BindMemberCardLog();
            $time = time();
            $note = $member['username'].'修改了会员的手机号为:'.$member['mobile'].'和账户id为:'.$this->accountId;
            $bindMemberCardLog_data = [
                'member_id' => $this->memberId,
                'document_type' => $this->document_type,
                'id_card' => $this->id_card,
                'status' => $this->status,
                'card_number'=> $this->cardNumber,
                'create_at' => $time,
                'source' => $this->source,
                'note' => $note,
            ];
            $model->save($bindMemberCardLog_data);
            if ($transaction->commit() === NULL) {
                if ($memberCard['invalid_time'] < time()) {
                    return ['code' => 1, 'status' => 'success', 'message' =>'会员卡已绑定，但您的会员卡已过期!'];
                }
                if ($memberCard['status'] == 2) {
                    return ['code' => 1, 'status' => 'success', 'message' =>'会员卡已绑定，但您的会员卡异常!'];
                }
                if ($memberCard['status'] == 3) {
                    return ['code' => 1, 'status' => 'success', 'message' =>'会员卡已绑定，但您的会员卡已冻结!'];
                }
                return ['code' => 1, 'status' => 'success', 'message' =>'恭喜您,绑定成功!'];
            } else {
                return ['code' => 0,'status' => 'success','message' => '网络异常,绑定失败!'];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
            return ['code' => 0,'status' => 'success','message' => '网络异常,绑定失败!'];
        }
    }
    /**
     * 会员端 - API - 验证手机号
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/4/10
     */
    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}