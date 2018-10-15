<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/19
 * Time: 15:45
 * Desc:会员信息
 */
namespace common\models\relations;

use backend\models\Member;

trait MemberInformationRelations
{
    /**
     * 会员端 - 会员第一次登陆补全信息你 - 关联会员表
     * @author xinwei <xniwei@itsports.club>
     * @create 2018/06/19
     * @return mixed
     */
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }
}