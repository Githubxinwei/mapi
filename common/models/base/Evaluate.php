<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 上午 10:29
 */

namespace common\models\base;



class Evaluate extends  \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%evaluate}}';
    }
}