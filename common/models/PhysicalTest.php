<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/6/30
 * Time: 16:49
 * Desc:体测数据表
 */
namespace common\models;

use common\models\relations\PhysicalTestRelations;

class PhysicalTest extends \common\models\base\PhysicalTest
{
    use PhysicalTestRelations;

}