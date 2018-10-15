<?php

namespace common\models\relations;

use common\models\base\ActionCategory;
use common\models\base\ActionCategoryRelation;
use common\models\base\ActionImages;

trait ActionRelations
{
    /**
     * 私教管理 - 动作库 - 动作表、分类表、关联表三表关联
     * @author jianbingqi <jianbingqi@itsports.club>
     * @return \yii\db\ActiveQuery
     */
    public function getCategorys()
    {
        return $this->hasMany(ActionCategory::className(), ['id' => 'cid'])
            ->viaTable(ActionCategoryRelation::tableName(), ['aid' => 'id'])->onCondition(['pid'=>1]);
    }
//    public function getCategoryes()
//    {
//        return $this->hasOne(ActionCategoryRelation::className(),['aid'=>'id']);
//    }

    /**
     * 私教管理 - 动作库 - 动作表关联图片表
     * @author jianbingqi <jianbingqi@itsports.club>
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ActionImages::className(), ['aid'=>'id']);
    }


}