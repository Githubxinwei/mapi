<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13 0013
 * Time: 16:05
 */
namespace common\models;
use common\models\relations\CabinetRentRelations;
class MemberCabinetRentHistory extends \common\models\base\MemberCabinetRentHistory
{
    use CabinetRentRelations;
}