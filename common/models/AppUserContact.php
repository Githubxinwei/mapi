<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/2
 * Time: 13:09
 * Desc:用户手机通讯录
 */
namespace common\models;

use common\models\relations\AppUserContactRelations;
class AppUserContact extends \common\models\base\AppUserContact
{
    use AppUserContactRelations;
}