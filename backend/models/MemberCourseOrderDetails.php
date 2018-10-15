<?php
namespace backend\models;

use common\models\relations\MemberCourseOrderDetailsRelations;
use Yii;
class MemberCourseOrderDetails extends \common\models\base\MemberCourseOrderDetails
{
    use MemberCourseOrderDetailsRelations;
}