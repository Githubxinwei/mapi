<?php
namespace common\models;

use \common\models\relations\OrderRelations;

class Order extends \common\models\base\Order
{
      use OrderRelations;
}