<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/16
 * Time: 9:20
 * Desc:会员合同记录表
 */
namespace common\models;

use common\models\relations\MemberDealRecordRelations;

class MemberDealRecord extends \common\models\base\MemberDealRecord
{
    use MemberDealRecordRelations;
}