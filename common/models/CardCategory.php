<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/3/30
 * Time: 10:58
 */

namespace common\models;
use \common\models\relations\CardCategoryRelations;

class CardCategory extends \common\models\base\CardCategory
{
    use CardCategoryRelations;
}