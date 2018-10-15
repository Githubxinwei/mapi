<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8 0008
 * Time: 14:21
 */

namespace common\models;

use common\models\relations\GiftCardListRelations;
class GiftCardList extends \common\models\base\GiftCardList
{
    use GiftCardListRelations;
}