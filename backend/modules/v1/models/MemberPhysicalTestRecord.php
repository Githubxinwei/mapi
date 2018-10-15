<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/25
 * Time: 15:08
 * Desc:体侧信息预约记录
 */
namespace backend\modules\v1\models;

use backend\modules\v1\models\MemberPhysicalTest;

class MemberPhysicalTestRecord extends \common\models\MemberPhysicalTestRecord
{
    public $memberId;    //会员id
    /**
     * 会员端 - 获取会员体测信息 - 验证规则
     * @author  xinwei <xinwei@itsport.club>
     * @create 2018/06/25
     */
    public function rules()
    {
        return [
            ['memberId', 'required', 'message' => '会员ID不能为空'],
        ];
    }
    /**
     * 会员端API - 会员预约体测
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/06/25
     */
    public function setMemberPhysicalTest()
    {
        $result = MemberPhysicalTestRecord::find()
            ->alias('mptr')
            ->joinWith(['member m'],FALSE)
            ->where(['and',['m.id' => $this->memberId],['mptr.status' => 1]])
            ->andWhere(['mptr.is_delete' => 0])
            ->select('mptr.id')
            ->count();
        if ($result) {
            return ['code' => 0, 'status' => 'success', 'message' => '您已经预约过了！','data' => $result];
        }
        $model = new \common\models\MemberPhysicalTestRecord();
        $model->member_id = $this->memberId;
        $model->status = 1;
        $data = $model->save();
        if ($data) {
            return ['code' => 1, 'status' => 'success', 'message' => '预约成功！','data' => $data];
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '网络错误，请稍后重试！','data' => []];
        }
    }
    /**
     * 移动端API - 查询会员最近一个月是否有体测
     * @author 辛威 <xinwei@itsport.club>
     * @createAt 2018/06/25
     */
    public function getMemberPhysicalTestRecord($accountId)
    {
        $memberPhysicalTestRecord = MemberPhysicalTestRecord::find()
            ->alias('mptr')
            ->joinWith(['member m'],FALSE)
            ->where(['m.member_account_id' => $accountId])
            ->andWhere(['mptr.status' => [1,2],'mptr.is_delete' => 0])
            ->andWhere(['between','mptr.create_at',date("Y-m-d H:i:s",time()-30*24*60*60),date("Y-m-d H:i:s",time())])
            ->select('mptr.id')
            ->count();
        $memberPhysicalTest = MemberPhysicalTest::find()
            ->alias('mpt')
            ->joinWith(['member m'],FALSE)
            ->where(['m.member_account_id' => $accountId])
            ->andWhere(['mpt.is_delete' => 0])
            ->andWhere(['between','mpt.create_at',date("Y-m-d H:i:s",time()-30*24*60*60),date("Y-m-d H:i:s",time())])
            ->select('mpt.id')
            ->count();
        if (($memberPhysicalTestRecord > 0) || ($memberPhysicalTest > 0)) {
            return true;
        }
        return false;
    }
}
