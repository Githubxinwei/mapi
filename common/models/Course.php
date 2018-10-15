<?php

namespace common\models;
use Yii;
use common\models\relations\CourseRelations;
class Course extends \common\models\base\Course
{
    use CourseRelations;
}