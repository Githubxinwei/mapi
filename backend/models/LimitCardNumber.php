<?php
namespace backend\models;
use common\models\relations\CardCategoryRelations;
class LimitCardNumber extends \common\models\base\LimitCardNumber
{
    use CardCategoryRelations;
}