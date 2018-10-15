<?php
	
	namespace backend\modules\v1\models;

use Yii;

/**
 * This is the model class for table "cloud_group".
 *
 * @property string $id 自增ID
 * @property string $pid 父ID
 * @property int $level 级别
 * @property string $title 分类名称
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property string $create_id 创建人ID
 * @property int $type 1 餐饮分组
 * @property string $company_id 公司ID
 * @property string $venue_id 场馆ID
 * @property int $is_delete 软删标记 0 未删除 1 已删除
 */
class Group extends \common\models\Group
{

}
