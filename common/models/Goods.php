<?php
	
namespace common\models;

use common\models\relations\GoodsRelations;

class Goods extends \common\models\base\Goods
{
    use GoodsRelations;
}