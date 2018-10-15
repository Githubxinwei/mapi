<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/5/3
 * Time: 14:30
 */

namespace backend\modules\v1\models;

use common\models\base\MemberDetails;
use yii\base\Model;
use Yii;

class MemberDetailUpdateForm extends Model
{
    public $account_id;     //账户id
    public $nickname;      //昵称
    public $name;            //昵称(迈步)
    public $sex;           //性别
    public $birth_date;   //生日
    public $profession;    //职业
    public $now_address;   //居住地
    public $introduction;  //个人简介
    /**
     * 后台 - 登录 - 验证规则
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function rules()
    {
        return [
            ['account_id','required','message'=>'账户ID不能为空'],
            [['account_id','nickname','name','sex','birth_date','profession','now_address','introduction'],'safe']
        ];
    }
    /**
     * 后台 - API - 修改会员姓名
     * @author  lihuien <lihuien@itsport.club>
     * @create 2017/3/30
     */
    public function updateMember()
    {
        $member = Member::find()->select('id')->where(['member_account_id'=>$this->account_id])->all();
        foreach ($member as $memberid){
            $id[] = $memberid['id'];
        }
        $detailData   = MemberDetails::find()->select('id')->where(['member_id'=>$id])->asArray()->all();
        foreach ($detailData as $memberids){
            $ids[] = $memberids['id'];
        };
        $count = MemberDetails::updateAll(['nickname'=>$this->name],['id'=>$ids]);
        if($count > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 会员端 - API - 修改会员详细信息
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/5/28
     */
    public function alterMember()
    {
        $member = Member::find()->select('id')->where(['member_account_id'=>$this->account_id])->all();
        $id = [];
        foreach ($member as $memberid){
            $id[] = $memberid['id'];
        }
        $detailData   = MemberDetails::find()->select('id')->where(['member_id'=>$id])->asArray()->all();
        $ids = [];
        foreach ($detailData as $memberids){
            $ids[] = $memberids['id'];
        };
        $count = MemberDetails::updateAll(
            [
            'nickname'     => $this->nickname,
            'sex'           => $this->sex,
            'birth_date'   => $this->birth_date,
            'profession'   => $this->profession,
            'now_address'  => $this->now_address,
            'introduction' => $this->introduction,
            'updated_at'   => time(),
            ],
            ['id'=>$ids]);
        if($count > 0) {
            return true;
        } else {
            return false;
        }
    }
}