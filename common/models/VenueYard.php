<?php
/**
 * Created by PhpStorm.
 * User: 辛威
 * Date: 2018/5/14
 * Time: 下午 4:49
 */
namespace common\models;

use common\models\relations\VenueYardRelations;

class VenueYard extends \common\models\base\VenueYard
{
    use VenueYardRelations;
}