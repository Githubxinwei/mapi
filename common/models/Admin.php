<?php

namespace common\models;

use common\models\relations\AdminRelations;

class Admin extends \common\models\base\Admin
{
    use AdminRelations;
}